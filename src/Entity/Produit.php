<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Produit
{
    use timestandable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomPro;

    /**
     * @ORM\Column(type="float")
     */
    private $prixPro;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $nouveau;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $meilleur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagePro;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produit",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=Marque::class, inversedBy="produit",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $marque;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionPro;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="produits")
     */
    private $commande;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $autrePrix;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="produits")
     */
    private $commenter;

    public function __construct()
    {
        $this->commenter = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPro(): ?string
    {
        return $this->nomPro;
    }

    public function setNomPro(string $nomPro): self
    {
        $this->nomPro = $nomPro;

        return $this;
    }

    public function getPrixPro(): ?float
    {
        return $this->prixPro;
    }

    public function setPrixPro(float $prixPro): self
    {
        $this->prixPro = $prixPro;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getNouveau(): ?bool
    {
        return $this->nouveau;
    }

    public function setNouveau(?bool $nouveau): self
    {
        $this->nouveau = $nouveau;

        return $this;
    }

    public function getMeilleur(): ?bool
    {
        return $this->meilleur;
    }

    public function setMeilleur(?bool $meilleur): self
    {
        $this->meilleur = $meilleur;

        return $this;
    }

    public function getImagePro(): ?string
    {
        return $this->imagePro;
    }

    public function setImagePro(string $imagePro): self
    {
        $this->imagePro = $imagePro;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getDescriptionPro(): ?string
    {
        return $this->descriptionPro;
    }

    public function setDescriptionPro(?string $descriptionPro): self
    {
        $this->descriptionPro = $descriptionPro;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }


    public function __toString()
    {
        return $this->getNomPro();
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getAutrePrix(): ?float
    {
        return $this->autrePrix;
    }

    public function setAutrePrix(?float $autrePrix): self
    {
        $this->autrePrix = $autrePrix;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommenter(): Collection
    {
        return $this->commenter;
    }

    public function addCommenter(Commentaire $commenter): self
    {
        if (!$this->commenter->contains($commenter)) {
            $this->commenter[] = $commenter;
            $commenter->setProduits($this);
        }

        return $this;
    }

    public function removeCommenter(Commentaire $commenter): self
    {
        if ($this->commenter->removeElement($commenter)) {
            // set the owning side to null (unless already changed)
            if ($commenter->getProduits() === $this) {
                $commenter->setProduits(null);
            }
        }

        return $this;
    }
}
