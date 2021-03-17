<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommissionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommissionRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *                              "getCommissions"={
 *                                      "method"="POST",
 *                                      "path"="/commission",
 *                                      "route_name":"addingCompte"
 *                                    }
 *                          },
 *      itemOperations={
                            "get"={}
 *                  }
 * )
 */
class Commission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Etat;

    /**
     * @ORM\Column(type="integer")
     */
    private $transfertArgent;

    /**
     * @ORM\Column(type="integer")
     */
    private $operateurDepot;

    /**
     * @ORM\Column(type="integer")
     */
    private $operateurRetrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?int
    {
        return $this->Etat;
    }

    public function setEtat(int $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getTransfertArgent(): ?int
    {
        return $this->transfertArgent;
    }

    public function setTransfertArgent(int $transfertArgent): self
    {
        $this->transfertArgent = $transfertArgent;

        return $this;
    }

    public function getOperateurDepot(): ?int
    {
        return $this->operateurDepot;
    }

    public function setOperateurDepot(int $operateurDepot): self
    {
        $this->operateurDepot = $operateurDepot;

        return $this;
    }

    public function getOperateurRetrait(): ?int
    {
        return $this->operateurRetrait;
    }

    public function setOperateurRetrait(int $operateurRetrait): self
    {
        $this->operateurRetrait = $operateurRetrait;

        return $this;
    }
}
