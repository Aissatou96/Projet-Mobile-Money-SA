<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource(
 * 
 *   attributes={},
 * 
 *  collectionOperations={
 *                          "addAgence"={
 *                                      "method"="POST",
 *                                      "path"="/agence"
 *                                    },
 * 
 *                          "getAllAgences"={
 *                                      "method"="GET",
 *                                      "path"="/agences",
 *                                      "normalization_context"= {"groups"= {"agences_read"}}
 *                                    }
 *                       },
 * 
 *  itemOperations={
 *                     "getOneAgence"={
 *                                  "method"="GET",
 *                                  "path"="/agences/{id}",
 *                                  "normalization_context"= {"groups"= {"one_agence_read"}}
 *                               },
 * 
 *                      "updateAgence"={
 *                                  "method"="PUT",
 *                                  "path"="/agences/{id}"
 *                               },
 * 
 *                      "deleteAgence"={
 *                                  "method"="DELETE",
 *                                  "path"="/agences/{id}"
 *                               }
 *                 }
 * )
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agences_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agences_read"})
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agences_read"})
     */
    private $statut = 'Actif';

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }
}
