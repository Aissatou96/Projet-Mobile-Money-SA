<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AgenceRepository;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use App\Services\GestionImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route(
     *        path="api/user", 
     *        methods={"POST"}
     *       )
     */

    public function addUser(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder,SerializerInterface $serializer, ProfilRepository $profilRepository, AgenceRepository $agenceRepository): Response
    {
         //Recupérer les données envoyées dans la requête avec $request
         $data = $request->request->all();

         //Recupération et gestion de l'image
       if($getavatar = $request->files->get("avatar")){
        $avatar = fopen($getavatar->getRealPath(), 'rb');
        $data["avatar"] = $avatar;
      }
      
       // $data est un array je le dénormalize avec la fonction denormalize() pour avoir un objet de type       User::class
       $user = $serializer->denormalize($data, User::class);

       //Recupération du profil qui sera affecté à l'utilisateur créé
       if($profil= $profilRepository->findOneBy(['libelle'=>$data['profils']])){
        $user->setProfil($profil);
        }
        // on verifie si agence a ete envoye dans la request
      if(isset($data['agences'])){
        $agence = $agenceRepository->findOneBy(['id'=>(int)$data['agences']]);
        $user->setAgence($agence);
      }
       //Recupérer le password pour encodage
       $password = $request->get('password');
       $user->setPassword($encoder->encodePassword($user,$password));
      
      $em->persist($user);
      $em->flush();

        return  $this->json(['message'=> 'Utilisateur créé avec succès!'], Response::HTTP_CREATED); 
     
    }


    /**
     * @Route(
     *        path="api/users/{id}", 
     *        methods={"PUT"}
     *       )
     */

    public function updateUser(GestionImage $gestionImage, Request $request, $id, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
      $userUpdate = $gestionImage->GestionImage($request,'avatar');
      $utilisateur = $userRepository->find($id);
      foreach ($userUpdate as $key => $value) {
        $setteur = 'set'.ucfirst(strtolower($key));
        if ($setteur=='setProfil') {
          $utilisateur->setProfil($userUpdate['profils']);
        }
        if(method_exists(User::class,$setteur)){
          if ($setteur=='setPassword') {
             $utilisateur->setPassword($encoder->encodePassword($utilisateur,$userUpdate['password']));
          }else{
              $utilisateur->$setteur($value);
          }
        }
      }
        $em->persist($utilisateur);
        $em->flush();
        return  $this->json(['message'=> 'Utilisateur modifié avec succès!'], Response::HTTP_CREATED);
    }

}
