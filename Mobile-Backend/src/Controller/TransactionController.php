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
use Symfony\Component\Validator\Constraints\Date;

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
        //Recup??ration de la requ??te en json
       $data = json_decode($request->getContent(), true);

       //Recup??ration de l'user connect?? via son token
       $user = $this->tokenStorage->getToken()->getUser();

        //Recup??ration du compte de l'agence qui fait la transaction
       $compte = $this->compteRepository->findOneBy(['agence'=>$user->getAgence()->getId()]);

        //On v??rifie si la transaction est un d??p??t
       if($data["type"] === "depot"){
           //On recup??re les informations du client qui envoie et retrait
        $clientEnvoi = $data["clientEnvois"];
        $clientRetrait = $data["clientRetraits"];
 
        //D??normalization
        $transaction = $this->serializer->denormalize($data, Transaction::class);

        //On v??rifie si le client qui envoie existe d??j?? dans notre base de donn??es  
        if($client = $this->clientRepository->findOneBy(["phone"=>$clientEnvoi["phone"]])){
            //si ??a existe on recup??re les infos du client et on envoit ??a dans la requ??te
         $transaction->setClientEnvoi($client);
        }else{
            //Si ??a n'existe pas on le recup??re et on le met dans la table client 
         $client= $this->serializer->denormalize($clientEnvoi, Client::class);
         $this->manager->persist($client);
         $transaction->setClientEnvoi($client);
        }

        //On v??rifie si le client qui re??oit existe d??j?? dans notre base de donn??es
        if($client=$this->clientRepository->findOneBy(["phone"=>$clientRetrait["phone"]])){

            //si ??a existe on recup??re les infos du client et on envoit ??a dans la requ??te
         $transaction->setClientRetrait($client);
         }else{
              //Si ??a n'existe pas on le recup??re et on le met dans la table client
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

         //On v??rifie si le montant ?? d??poser est > 0
        if($data["montant"] > 0){

            //On v??rifie si le solde du compte est > au montant ?? d??poser
             if($compte->getSolde() > $data['montant']){
                 
                //On soustrait le montant ?? d??poser et on ajoute la commission de depot dans le compte de l'agence pour l'ajouter au client 
                $compte->setSolde($compte->getSolde()- $data['montant'] + $commisson['depot']);
             }else{
                return  $this->json(['message'=> 'Impossible d\'effectuer ce transfert car le solde du compte est insuffisant'],404);
             }
            
        }else{
             return  $this->json(['message'=> 'le montant envoy?? doit ??tre sup??rieur ?? 0']);
        }
       
        //On v??rifie si la transaction est un retrait
       }elseif ($data["type"] === "retrait") {
           
        //V??rification et recup??ration du code de la transaction
           if($transaction = $this->transactionRepository->findOneBy(['code'=>$data["code"]])){

               //On recup??re les infos du client qui a ??t?? d??sign?? pour faire le retrait
               $clientRetrait = $transaction->getClientRetrait();



                //on v??rifie si la transaction n'a pas ??t?? retir??e en testant si la date de retrait est nulle
                if($transaction->getDateRetrait() == null && $transaction->getDateAnnulation() == null){

                    //On ajoute le montant ?? retirer dans le compte de l'agence + commmission retrait
                    $compte->setSolde($compte->getSolde()+ $transaction->getMontant()+ $transaction->getCommissionRetrait());

                    //On set l'user qui a r??alis?? la transaction de retrait
                    $transaction->setUserRetrait($user);
                    $clientRetrait->setCni($data['cni']);
                    $transaction->setDateRetrait(new \DateTime('now'));
                    $this->manager->flush();
                    return  $this->json(['message'=> 'Retrait  effectu?? avec succ??s!'], Response::HTTP_CREATED); 
                }else{
                    return  $this->json(['message'=> 'l\'argent a d??ja ??t?? retir??e ou bien la transaction a ??t?? anul??e'], 404);
                }
                

           }else{
            return  $this->json(['message'=> 'Ce code de retrait n\'existe pas!'], 404);
           }
       }

       $this->manager->persist($transaction);
       $this->manager->flush();
       return  $this->json(['message'=> 'Transaction effectu??e avec succ??s!'], Response::HTTP_CREATED);
    }
/***************************************************************************************/
    public function calculerFrais(Request $request){
        $data = json_decode($request->getContent(), true);
        $frais = 0;
        if($data['montant'] > 0){
            $frais = $this->calculFraisService->calculerFrais($data['montant']);
            
        }
        return  $this->json(['Frais'=> $frais],200);
    }

    /******************************************************************************************************* */

    public function getTransac(Request $request){
        $data = json_decode($request->getContent(), true);
        $result = array();
        if (!empty($data['code'])) {
           $transaction = $this->transactionRepository->findOneBy(['code' => $data['code']]);
            $clientEnvoi = $this->clientRepository->findOneBy(['id'=>$transaction->getClientEnvoi()->getId()]);
            $clientRetrait= $this->clientRepository->findOneBy(['id'=>$transaction->getClientRetrait()->getId()]);
            $result['montant'] = $transaction->getMontant();
            $result['dateEnvoi'] = $transaction->getDateDepot();
            $result['clientEnvoi']['nom'] = $clientEnvoi->getLastname() ." ".$clientEnvoi->getFirstname();
            $result['clientEnvoi']['cni'] = $clientEnvoi->getCni();
            $result['clientEnvoi']['phone'] = $clientEnvoi->getPhone();

            $result['clientRetrait']['nom'] = $clientRetrait->getLastname() ." ".$clientRetrait->getFirstname();
            $result['clientRetrait']['phone'] = $clientRetrait->getPhone();
        }
        return  $this->json(['transaction'=> $result],200);
    }

/************************************************************************************************************/

public function deleteTransaction($id){
    //Recuperation du code de transaction qu'on veut annuler
    $transaction = $this->transactionRepository->findOneBy(['id'=>$id]);
    if($transaction){
    //R??cup??ration de l'id de l'utilisateur connect?? via son token
    $userId = $this->tokenStorage->getToken()->getUser()->getId();
     //On verifie si l'id de l'user qui veut annuler le d??p??t est ??gal ?? l'id de l'user qui a fait le d??p??t
     if ($userId == $transaction->getUserDepot()->getId()) {
        //si oui on recup??re le compte dans lequel le d??pot a ??t?? fait
         $compte = $transaction->getCompte();
         //On v??rifie si la transaction a ??t?? retir??e ou pas?
         if($transaction->getDateRetrait()==null){
            $compte->setSolde($compte->getSolde() - $transaction->getMontant());
            $transaction->setDateAnnulation(new \DateTime('now'));
            $this->manager->flush();
            return  $this->json(['message'=> 'D??pot annul?? avec succ??s'], 200);

         }else{
            return  $this->json(['message'=> 'La transaction a ??t?? d??j?? retir??e, impossible de l\'annuler'], 404);
         }
     }
    }else{
        return  $this->json(['message'=> 'La transaction n\'existe pas'],404);
    }
}
}
