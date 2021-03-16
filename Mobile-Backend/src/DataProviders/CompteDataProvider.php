<?php
// api/src/DataProvider/BlogPostCollectionDataProvider.php

namespace App\DataProviders;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Compte;
use App\Repository\CompteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CompteDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $token;
    /**
     * @var CompteRepository
     */
    private $compteRepository;

    /**
     * CompteDataProvider constructor.
     * @param TokenStorageInterface $token
     * @param CompteRepository $compteRepository
     */
    public function __construct(
        TokenStorageInterface $token,
        CompteRepository $compteRepository
    )
    {
        $this->token = $token;
        $this->compteRepository = $compteRepository;

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Compte::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): JsonResponse
    {
        if ($operationName === 'getSolde'){
            $user = $this->token->getToken()->getUser()->getAgence()->getId();
            $compte = $this->compteRepository->findOneBy(["agence"=>$user]);
            $solde = $compte->getSolde();
           return new JsonResponse(['data'=>$solde]);
        }else{
            $data = $this->compteRepository->findAll();
            return new JsonResponse(['data'=>$data]);
        }
    }
}