<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Commande
{
    use timestandable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $codeCommande;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="float")
     */
    private $subTotal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusCommande;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="commandes", cascade={"persist"})
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commander")
     */
    private $users;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCommande(): ?\DateTimeInterface
    {
        return $this->codeCommande;
    }

    public function setCodeCommande(\DateTimeInterface $codeCommande): self
    {
        $this->codeCommande = $codeCommande;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getSubTotal(): ?float
    {
        return $this->subTotal;
    }

    public function setSubTotal(float $subTotal): self
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    public function getStatusCommande(): ?string
    {
        return $this->statusCommande;
    }

    public function setStatusCommande(?string $statusCommande): self
    {
        $this->statusCommande = $statusCommande;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produits->removeElement($produit);

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
}
