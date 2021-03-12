<?php


namespace App\Services;


use App\Repository\CommissionRepository;
use App\Repository\TarifRepository;

class CalculFraisService
{
    /**
     * @var TarifRepository
     */
    private TarifRepository $tarifRepository;
    /**
     * @var CommissionRepository
     */
    private CommissionRepository $commissionRepository;

    /**
     * CalculFraisService constructor.
     * @param TarifRepository $tarifRepository
     * @param CommissionRepository $commissionRepository
     */

    public function __construct(TarifRepository  $tarifRepository,
                                CommissionRepository $commissionRepository)
    {
        $this->tarifRepository = $tarifRepository;
        $this->commissionRepository = $commissionRepository;

    }

    public function calculerFrais($montant){
        $data = $this->tarifRepository->findAll();
        $frais = 0;
        foreach ($data as $value){
            if($montant>=2000000){
                $frais = ($value->getFraisEnvoi()*$montant)/100;
            }else{
                switch($montant){
                    case $montant>= $value->getMontantMIn() && $montant<$value->getMontantMax():
                        $frais = $value->getFraisEnvoi();
                        break;
                }
            }

        }
        return $frais;
    }

    public function calculerPart($mntTotalFrais): array
    {
        $data = $this->commissionRepository->findAll();
        $Part = array();
        foreach($data as $value){
            $Part['etat']= ($mntTotalFrais* $value->getEtat())/100;
            $Part['transfert']= ($mntTotalFrais*$value->getTransfertArgent())/100;
            $Part['depot']= ($mntTotalFrais*$value->getOperateurDepot())/100;
            $Part['retrait']= ($mntTotalFrais*$value->getOperateurRetrait())/100;
        }
        return $Part;
    }

}