<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Commentaire
{
    use timestandable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Nombre d'etoile ne peut pas être vide et nombre maximum 5")
     * @Assert\Length(max=5)     
     */
    private $numbreEtoile;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Commmentaire ne peut pas être vide ")
     * @Assert\Length(min=10,minMessage="Votre commentaire est trop court")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublier;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="commenter")
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaires")
     */
    private $users;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIsPublier(): ?bool
    {
        return $this->isPublier;
    }

    public function setIsPublier(bool $isPublier): self
    {
        $this->isPublier = $isPublier;

        return $this;
    }

    public function getProduits(): ?Produit
    {
        return $this->produits;
    }

    public function setProduits(?Produit $produits): self
    {
        $this->produits = $produits;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }



    public function getNumbreEtoile(): ?int
    {
        return $this->numbreEtoile;
    }

    public function setNumbreEtoile(int $numbreEtoile): self
    {
        $this->numbreEtoile = $numbreEtoile;

        return $this;
    }
}
