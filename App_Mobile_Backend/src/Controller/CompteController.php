<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CompteController extends AbstractController
{
  
    public function addCompte(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, AgenceRepository $agenceRepository): Response
    {
        $data = json_decode($request->getContent(),true);
        $cpte = $serializer->denormalize($data, Compte::class);
        if($agence= $agenceRepository->findOneBy(['id'=>$data['agences']])){
            $cpte->setAgence($agence);
        }
        $cpte->setNumCpte(uniqid("C",false));
        $em->persist($cpte);
        $em->flush();
        return  $this->json(['message'=> 'Compte créé avec succès!'], Response::HTTP_CREATED); 

    }
}
