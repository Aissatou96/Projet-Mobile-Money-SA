<?php
// api/src/DataProvider/BlogPostCollectionDataProvider.php

namespace App\DataProviders;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TransactionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $tokenStorage;
    private $transactionRepository;
    private $userRepository;
    public function __construct(TokenStorageInterface $tokenStorage, TransactionRepository $transactionRepository, UserRepository $userRepository )
    {
        $this->tokenStorage = $tokenStorage;
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Transaction::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): JsonResponse
    {
       
            $data = [];
            $userConnect = $this->tokenStorage->getToken()->getUser();
            $t = 0;
            if($userConnect->getRoles()[0]=='ROLE_AdminSystem'){
               
                $transactions = $this->transactionRepository->findAll();
               //dd($transactions);
               $i=0;
                foreach ($transactions as $key => $transaction) {

                    $data[$i]['montant'] = $transaction->getMontant();
                    $data[$i]['TotalCommission'] = $transaction->getFrais();

                    if ($transaction->getDateDepot()!= null) {
                        $user = $this->userRepository->findOneBy(['id'=>$transaction->getUserDepot()->getId()]);
                    
                        $data[$i]['nom']= $user->getFirstname().' '.$user->getLastname();
                        $data[$i]['type'] = 'Dépôt';
                        $data[$i]['date'] = $transaction->getDateDepot()->format('Y-m-d');
                        $data[$i]['commission'] = $transaction->getCommissionDepot();
                    }

                    $i++;
                    $t++;
                
                    if ($transaction->getDateRetrait()!= null) {
                        $user = $this->userRepository->findOneBy(['id'=>$transaction->getUserRetrait()->getId()]);
                        $data[$t]['nom']= $user->getFirstname().' '.$user->getLastname();
                        $data[$t]['type'] = 'Retrait';
                        $data[$t]['date'] = $transaction->getDateRetrait()->format('Y-m-d');
                        $data[$t]['commission'] = $transaction->getCommissionRetrait();
                    }
                   $i++;
                   
                }

            }else{
                
            $transactionDepots = $this->transactionRepository->findBy(['userDepot'=>$userConnect->getId()]);
            $transactionRetraits = $this->transactionRepository->findBy(['userRetrait'=>$userConnect->getId()]);
               //dd($transactionRetraits);
               $i=0;
                foreach ($transactionDepots as $key => $transaction) {

                    $data[$i]['montant'] = $transaction->getMontant();

                    if ($transaction->getDateDepot()!= null) {
                        
                        $data[$i]['type'] = 'Dépôt';
                        $data[$i]['date'] = $transaction->getDateDepot()->format('Y-m-d');
                    }
                $i++;
                $t++;
                   
                }
        
                foreach ($transactionRetraits as $key => $transa) {

                    $data[$t]['montant'] = $transa->getMontant();
                
                    if ($transa->getDateRetrait()!= null) {
                       
                        $data[$t]['type'] = 'Retrait';
                        $data[$t]['date'] = $transa->getDateRetrait()->format('Y-m-d');
                    }
                   $t++;
                   
                }
            }
            
            return new JsonResponse(['data'=>$data],200);
        }
}
        
