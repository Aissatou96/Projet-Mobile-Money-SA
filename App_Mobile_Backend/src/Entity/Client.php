<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource(
 * 
 *   attributes={},
 * 
 *  collectionOperations={
 *                          "addClt"={
 *                                      "method"="POST",
 *                                      "path"="/client",
 *                                      "denormalization_context"= {"groups"= {"client_write"}}
 *                                    },
 * 
 *                          "getAllClts"={
 *                                      "method"="GET",
 *                                      "path"="/clients",
 *                                      "normalization_context"= {"groups"= {"clients_read"}}
 *                                    }
 *                       },
 * 
 *  itemOperations={
 *                     "getOneClt"={
 *                                  "method"="GET",
 *                                  "path"="/clients/{id}",
 *                                  "normalization_context"= {"groups"= {"one_client_read"}}
 *                               },
 * 
 *                      "updateClt"={
 *                                  "method"="PUT",
 *                                  "path"="/clients/{id}"
 *                               },
 * 
 *                      "deleteClt"={
 *                                  "method"="DELETE",
 *                                  "path"="/clients/{id}"
 *                               }
 *                 }
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transac_write"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transac_write"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transac_write"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transac_write"})
     */
    private $cni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut = 'Actif';

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientEnvoi")
     */
    private $transactions;


    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): self
    {
        $this->cni = $cni;

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
            $transaction->setClientEnvoi($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getClientEnvoi() === $this) {
                $transaction->setClientEnvoi(null);
            }
        }

        return $this;
    }

}
