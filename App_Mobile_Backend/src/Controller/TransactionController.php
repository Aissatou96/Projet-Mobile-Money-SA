<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Transaction;
use App\Repository\AgenceRepository;
use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;
use App\Services\CalculFraisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionController extends AbstractController
{
     /**
     * @var CalculFraisService
     */
    private CalculFraisService $calculFraisService;
    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var AgenceRepository
     */
    private AgenceRepository $agenceRepository;
    /**
     * @var CompteRepository
     */
    private CompteRepository $compteRepository;
    /**
     * @var TransactionRepository
     */
    private TransactionRepository $transactionRepository;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var ClientRepository
     */
    private ClientRepository $clientRepository;

    /**
     * TransactionController constructor.
     *
     * @param EntityManagerInterface $manager
     * @param CalculFraisService $calculFraisService
     * @param TokenStorageInterface $tokenStorage
     * @param GenererNum $generator
     * @param TransactionRepository $transactionRepository
     * @param CompteRepository $compteRepository
     * @param AgenceRepository $agenceRepository
     * @param SerializerInterface $serializer
     * @param ClientRepository $clientRepository
     */
    public function __construct(
        
        EntityManagerInterface $manager,
        CalculFraisService $calculFraisService,
        TokenStorageInterface $tokenStorage,
        TransactionRepository $transactionRepository,
        CompteRepository $compteRepository, 
        AgenceRepository $agenceRepository,
        SerializerInterface $serializer,
        ClientRepository $clientRepository
    )
    {

        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
        $this->agenceRepository = $agenceRepository;
        $this->calculFraisService = $calculFraisService;
        $this->compteRepository = $compteRepository;
        $this->transactionRepository = $transactionRepository;
        $this->serializer = $serializer;
        $this->clientRepository = $clientRepository;
    }

    public function creerTransaction(Request $request): Response
    {
        //Recupération de la requête en json
       $data = json_decode($request->getContent(), true);

       //Recupération de l'user connecté via son token
       $user = $this->tokenStorage->getToken()->getUser();

        //Recupération du compte de l'agence qui fait la transaction
       $compte = $this->compteRepository->findOneBy(['agence'=>$user->getAgence()->getId()]);

        //On vérifie si la transaction est un dépôt
       if($data["type"] === "depot"){
           //On recupère les informations du client qui envoie et retrait
        $clientEnvoi = $data["clientEnvois"];
        $clientRetrait = $data["clientRetraits"];
 
        //Dénormalization
        $transaction = $this->serializer->denormalize($data, Transaction::class);

        //On vérifie si le client qui envoie existe déjà dans notre base de données  
        if($client = $this->clientRepository->findOneBy(["phone"=>$clientEnvoi["phone"]])){
            //si ça existe on recupère les infos du client et on envoit ça dans la requête
         $transaction->setClientEnvoi($client);
        }else{
            //Si ça n'existe pas on le recupére et on le met dans la table client 
         $client= $this->serializer->denormalize($clientEnvoi, Client::class);
         $this->manager->persist($client);
         $transaction->setClientEnvoi($client);
        }

        //On vérifie si le client qui reçoit existe déjà dans notre base de données
        if($client=$this->clientRepository->findOneBy(["phone"=>$clientRetrait["phone"]])){

            //si ça existe on recupère les infos du client et on envoit ça dans la requête
         $transaction->setClientRetrait($client);
         }else{
              //Si ça n'existe pas on le recupére et on le met dans la table client
             $client= $this->serializer->denormalize($clientRetrait, Client::class);
             $this->manager->persist($client);
             $transaction->setClientRetrait($client);
         }

         $transaction->setCode(uniqid("CX", false));

         //calcul frais
         $frais = $this->calculFraisService->calculerFrais($data['montant']);
         $transaction->setFrais($frais);

         //calcul commission et affectation respective
         $commisson = $this->calculFraisService->calculerPart($frais);
         $transaction->setCommissionEtat($commisson['etat']);
         $transaction->setCommissionTransfert($commisson['transfert']);
         $transaction->setCommissionDepot($commisson['depot']);
         $transaction->setCommissionRetrait($commisson['retrait']);

         $transaction->setDateDepot(new \DateTime('now'));
         $transaction->setCompte($compte);
         $transaction->setUserDepot($user);

         //On vérifie si le montant à déposer est > 0
        if($data["montant"] > 0){

            //On vérifie si le solde du compte est > au montant à déposer
             if($compte->getSolde() > $data['montant']){
                 
                //On soustrait le montant à déposer et on ajoute la commission de depot dans le compte de l'agence pour l'ajouter au client 
                $compte->setSolde($compte->getSolde()- $data['montant'] + $commisson['depot']);
             }else{
                return  $this->json(['message'=> 'Impossible d\'effectuer ce transfert car le solde du compte est insuffisant']);
             }
            
        }else{
             return  $this->json(['message'=> 'le montant envoyé doit être supérieur à 0']);
        }
       
        //On vérifie si la transaction est un retrait
       }elseif ($data["type"] === "retrait") {
           
        //Vérification et recupèration du code de la transaction
           if($transaction = $this->transactionRepository->findOneBy(['code'=>$data["code"]])){

               //On recupère les infos du client qui a été désigné pour faire le retrait
               $clientRetrait = $transaction->getClientRetrait();

               // Vérification et recupération du phone et du cni du client qui a été désigné pour faire le retrait si ça correspond à celui du client qui est venu faire le retrait
            if($clientRetrait->getPhone() === $data['phone'] && $clientRetrait->getCni() === $data['cni']){

                //on vérifie si la transaction n'a pas été retirée en testant si la date de retrait est nulle
                if($transaction->getDateRetrait() == null){

                    //On ajoute le montant à retirer dans le compte de l'agence + commmission retrait
                    $compte->setSolde($compte->getSolde()+ $transaction->getMontant()+ $transaction->getCommissionRetrait());

                    //On set l'user qui a réalisé la transaction de retrait
                    $transaction->setUserRetrait($user);

                    $transaction->setDateRetrait(new \DateTime('now'));
                    $this->manager->flush();
                    return  $this->json(['message'=> 'Retrait  effectué avec succès!'], Response::HTTP_CREATED); 
                }else{
                    return  $this->json(['message'=> 'l\'argent a déja été retirée']); 
                }
                
            }else{
                return  $this->json(['message'=> 'Veuillez revoir vos donnees !']); 
            }
           }else{
            return  $this->json(['message'=> 'Ce code de retrait n\'existe pas!']); 
           }
       }

       $this->manager->persist($transaction);
       $this->manager->flush();
       return  $this->json(['message'=> 'Transaction effectuée avec succès!'], Response::HTTP_CREATED);
    }
}
