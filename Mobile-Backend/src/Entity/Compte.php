<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiResource(
 * 
 *   attributes={},
 * 
 *  collectionOperations={
 *                          "addCompte"={
 *                                      "method"="POST",
 *                                      "path"="/compte",
 *                                      "route_name":"addingCompte"
 *                                    },
 * 
 *                          "getComptes"={
 *                                      "method"="GET",
 *                                      "path"="/comptes",
 *                                      "normalization_context"= {"groups"= {"comptes_read"}}
 *                                    }
 *                       },
 * 
 *  itemOperations={
 *                     "getCompte"={
 *                                  "method"="GET",
 *                                  "path"="/comptes/{id}",
 *                                  "normalization_context"= {"groups"= {"one_compte_read"}}
 *                               },
 * 
 *                      "updateCompte"={
 *                                  "method"="PUT",
 *                                  "path"="/comptes/{id}"
 *                               },
 * 
 *                      "deleteCompte"={
 *                                  "method"="DELETE",
 *                                  "path"="/comptes/{id}"
 *                               }
 *                 }
 * )
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"comptes_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"comptes_read"})
     */
    private $numCpte;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"comptes_read"})
     */
    private $solde;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"comptes_read"})
     */
    private $statut = 'Actif';

    /**
     * @ORM\Column(type="datetime")
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @Groups({"comptes_read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"comptes_read"})
     */
    private $archive = 0;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compte")
     */
    private $transactions;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, cascade={"persist", "remove"})
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="compte")
     */
    private $depots;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->depots = new ArrayCollection();
        $this->createdAt= new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCpte(): ?string
    {
        return $this->numCpte;
    }

    public function setNumCpte(string $numCpte): self
    {
        $this->numCpte = $numCpte;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;
        return $this;
    }


    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

        return $this;
    }
}
