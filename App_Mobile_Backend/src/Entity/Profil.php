<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiResource(
 * 
 *   attributes={},
 * 
 *  collectionOperations={
 *                          "addProfil"={
 *                                      "method"="POST",
 *                                      "path"="/profil"
 *                                    },
 * 
 *                          "getProfils"={
 *                                      "method"="GET",
 *                                      "path"="/profils",
 *                                      "normalization_context"= {"groups"= {"profils_read"}}
 *                                    }
 *                       },
 * 
 *  itemOperations={
 *                     "getProfil"={
 *                                  "method"="GET",
 *                                  "path"="/profils/{id}",
 *                                  "normalization_context"= {"groups"= {"one_profil_read"}}
 *                               },
 * 
 *                      "updateProfil"={
 *                                  "method"="PUT",
 *                                  "path"="/profils/{id}"
 *                               },
 * 
 *                      "deleteProfil"={
 *                                  "method"="DELETE",
 *                                  "path"="/profils/{id}"
 *                               }
 *                 }
 * 
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read", "profils_read", "one_profil_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read", "profils_read", "one_profil_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
