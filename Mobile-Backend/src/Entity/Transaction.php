<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(
 *  
 *  attributes={},
 * 
 *  collectionOperations={
 *                          "addTrans"={
 *                                      "method"="POST",
 *                                      "path"="/transac",
 *                                      "route_name":"creerTransaction",
 *                                      "denormalization_context"= {"groups"= {"transac_write"}}
 *                                    },
 * 
 *                          "getAllTransac"={
 *                                      "method"="GET",
 *                                      "path"="/transac",
 *                                      "normalization_context"= {"groups"= {"transac_read"}}
 *                                    },
 *                          
 *                           "calculerFrais"={
 *                                      "method"="POST",
 *                                      "path"="/transac/calcul",
 *                                      "route_name":"calculerFrais",
 *                                      "denormalization_context"= {"groups"= {"transac_write"}}
 *                                    },
 * 
 *                          "getCode"={
 *                                      "method"="POST",
 *                                      "path"="/transac/recup",
 *                                      "route_name":"getCode"
 *                                    },
 *                       },
 * 
 *  itemOperations={
 *                     "getTransac"={
 *                                  "method"="GET",
 *                                  "path"="/transac/{id}"
 *                               },
 * 
 *                      "updateTransac"={
 *                                  "method"="PUT",
 *                                  "path"="/transac/{id}"
 *                               },
 * 
 *                      "deleteTransac"={
 *                                  "method"="DELETE",
 *                                  "path"="/transac/{id}",
 *                                  "route_name":"delTransac"
 *                               }
 *                 }
 * )
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transac_write", "transac_read"})
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"transac_read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"transac_read"})
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"transac_read"})
     */
    private $frais;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @Groups({"transac_read"})
     */
    private $userRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @Groups({"transac_read"})
     */
    private $userDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions")
     * @Groups({"transac_write"})
     */
    private $clientEnvoi;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions")
     * @Groups({"transac_write"})
     */
    private $clientRetrait;

    /**
     * @ORM\Column(type="float")
     */
    private $commissionEtat;

    /**
     * @ORM\Column(type="float")
     */
    private $CommissionTransfert;

    /**
     * @ORM\Column(type="float")
     */
    private $commissionDepot;

    /**
     * @ORM\Column(type="float")
     */
    private $commissionRetrait;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateAnnulation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(?\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(?\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(?int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->userRetrait;
    }

    public function setUserRetrait(?User $userRetrait): self
    {
        $this->userRetrait = $userRetrait;

        return $this;
    }

    public function getUserDepot(): ?User
    {
        return $this->userDepot;
    }

    public function setUserDepot(?User $userDepot): self
    {
        $this->userDepot = $userDepot;

        return $this;
    }

    public function getClientEnvoi(): ?Client
    {
        return $this->clientEnvoi;
    }

    public function setClientEnvoi(?Client $clientEnvoi): self
    {
        $this->clientEnvoi = $clientEnvoi;

        return $this;
    }

    public function getClientRetrait(): ?Client
    {
        return $this->clientRetrait;
    }

    public function setClientRetrait(?Client $clientRetrait): self
    {
        $this->clientRetrait = $clientRetrait;

        return $this;
    }

    public function getCommissionEtat(): ?float
    {
        return $this->commissionEtat;
    }

    public function setCommissionEtat(float $commissionEtat): self
    {
        $this->commissionEtat = $commissionEtat;

        return $this;
    }

    public function getCommissionTransfert(): ?float
    {
        return $this->CommissionTransfert;
    }

    public function setCommissionTransfert(float $CommissionTransfert): self
    {
        $this->CommissionTransfert = $CommissionTransfert;

        return $this;
    }

    public function getCommissionDepot(): ?float
    {
        return $this->commissionDepot;
    }

    public function setCommissionDepot(float $commissionDepot): self
    {
        $this->commissionDepot = $commissionDepot;

        return $this;
    }

    public function getCommissionRetrait(): ?float
    {
        return $this->commissionRetrait;
    }

    public function setCommissionRetrait(float $commissionRetrait): self
    {
        $this->commissionRetrait = $commissionRetrait;

        return $this;
    }


    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(?\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }
}
