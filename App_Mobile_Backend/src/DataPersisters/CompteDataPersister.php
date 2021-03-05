<?php

namespace App\DataPersisters;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;

class CompteDataPersister implements ContextAwareDataPersisterInterface{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     * supports — cette méthode défini si ce persister supporte l'entité. Au fait c'est cette méthode qui dira si ce persister est pour l'entité Compte ou pas
     */
    public function supports($data, array $context = []): bool{

        return $data instanceof Compte;
    }

    /**
     * {@inheritdoc}
     * persist — cette méthode va créer ou modifier les données, c'est donc cette méthode qui sera appelée à    chaque opération POST, PUT ou PATCH
     */
    public function persist($data, array $context = []){
       
        return $data;
    }

    /**
     * {@inheritdoc}
     * remove — cette méthode sera appelée pour l'opération DELETE
     */
    public function remove($data, array $context = []){
        $data->setArchive(1);
        $this->entityManager->flush();
    }
}