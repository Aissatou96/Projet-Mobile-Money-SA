<?php

namespace App\DataPersisters;

use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Repository\UserRepository;

/**
 *
 */
class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $userRepository;
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->_entityManager = $entityManager;
        $this->userRepository = $userRepository;
      
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    /**
     * @param Profil $data
     */
    public function persist($data, array $context = [])
    {
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $id = $data->getId();
        $data->setArchive(1);
        // $users = $this->userRepository->findBy(['profil_id'=>$id]);
        // foreach ($users as $key => $user) {
        //     $user->setArchive(1);
        // }
        $this->_entityManager->flush();
    }
}