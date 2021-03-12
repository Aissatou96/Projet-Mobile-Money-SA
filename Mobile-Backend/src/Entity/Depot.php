<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DepotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 * attributes={"security"="is_granted('ROLE_Caissier') or is_granted('ROLE_AdminSystem')" },
 *  collectionOperations={
 *                          "addDepot"={
 *                                      "method"="POST",
 *                                      "path"="/depot",
 *                                      "route_name":"addingDepot"
 *                                    },
 * 
 *                          "getDepots"={
 *                                      "method"="GET",
 *                                      "path"="/depots",
 *                                      "normalization_context"= {"groups"= {"depots_read"}}
 *                                    }
 *                       },
 * 
 *  itemOperations={
 *                     "getDepot"={
 *                                  "method"="GET",
 *                                  "path"="/depots/{id}",
 *                                  "normalization_context"= {"groups"= {"one_depot_read"}}
 *                               },
 * 
 *                      "updateDepot"={
 *                                  "method"="PUT",
 *                                  "path"="/depots/{id}"
 *                               },
 * 
 *                      "deleteDepot"={
 *                                  "method"="DELETE",
 *                                  "path"="/depots/{id}",
 *                                  "route_name":"annulerDepot"
 *                              
 *                               }
 *                 }

 * )
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depots")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="depots")
     */
    private $compte;

    public function __construct()
    {
        $this->createdAt= new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
