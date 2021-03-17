<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DepotController extends AbstractController
{
    private $depotRepository;
    public  function __construct(DepotRepository $depotRepository)
    {
        $this->depotRepository = $depotRepository;
    }
  
    public function addDepot(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, CompteRepository $compteRepository,TokenStorageInterface $tokenStorage): Response
    {
        $data = json_decode($request->getContent(),true);

        //Récupère l'utilisateur connecté via le token 
        $user = $tokenStorage->getToken()->getUser();
        
        // Vérifie si le compte envoyé dans la requếte existe (cas adminSystem on doit donner le numéro de compte)
        if(isset($data['comptes'])){
            $compte = $compteRepository->findOneBy(['id'=>$data['comptes']]);
        }else{
            //Recupération de l'id de l'agence appartenant à l'user connecté (caissier de l'agence qui a accès au compte de l'agence) 
            $compte = $compteRepository->findOneBy(['agence'=>$user->getAgence()->getId()]);
        }
        
        $depot = $serializer->denormalize($data, Depot::class);

        if($data['montant'] > 0){
            $compte->setSolde($compte->getSolde() + $data['montant']);
            $depot->setUser($user);
            $depot->setCompte($compte);
        }else{
            return  $this->json(['message'=> 'Le montant doit être supérieure à 0']); 
        }
        $em->persist($depot);
        $em->flush();
        return  $this->json(['message'=> 'Dépôt effectué avec succès!'], Response::HTTP_CREATED); 
    }
    

    public function deleteDepot($id, EntityManagerInterface $em, DepotRepository $depotRepository, TokenStorageInterface $tokenStorage ): Response
    {
       //Récupération de l'id du dépot qu'on veut annuler 
       $depot = $depotRepository->findOneBy(["id"=>$id]);

       //Récupération de l'id de l'utilisateur connecté via son token
       $userId = $tokenStorage->getToken()->getUser()->getId();

       //Récupération de l'id du dernier dépôt avec la fction getLastIdDepot()
       $lastId = $this->getLastIdDepot();

       //On vérifie si l'id du dépôt qu'on veut annuler est l'id du dernier depôt
       if($id == $lastId){

        //On verifie si l'id de l'user qui veut annuler le dépôt est égal à l'id de l'user qui a fait le dépôt
           if($userId == $depot->getUser()->getId()){

               //si oui on recupère le compte dans lequel le dépot a été fait
               $compte = $depot->getCompte();

               // on verifie si le montant dans le compte est superieur au montant que lon veut annuler
               if($compte->getSolde() > $depot->getMontant()){
                   //si oui on soustrait
                    $compte->setSolde($compte->getSolde()- $depot->getMontant());

                    $em->remove($depot);
                    $em->flush();
                    return  $this->json(['message'=> 'Annulation effectuée avec succès'], 200);
               }else{
                return  $this->json(['message'=> 'Annulation impossible car le solde du compte est insuffissant']);
               }
           }else{
            return  $this->json(['message'=> 'Vous ne pouvez supprimé que le  dépôts que vous avez effectue']);
           }
       }else{
    return  $this->json(['message'=> 'Vous ne pouvez supprimé que le dernier dépôt']);  
       }
    
    }

    public function getLastIdDepot(): ?int
    {
        $ids = $this->depotRepository->findBy([], ['id'=>'DESC']);
        if(!$ids){
            $id= 0;
        }else{
            $id = ($ids[0]->getId());
        }
        return $id;
    }
}
