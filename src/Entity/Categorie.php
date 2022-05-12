<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Categorie
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
    private $nomCate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageCate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionCate;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="categorie", cascade={"persist"},orphanRemoval=true)
     */
    private $produit;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $icons;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCate(): ?string
    {
        return $this->nomCate;
    }

    public function setNomCate(string $nomCate): self
    {
        $this->nomCate = $nomCate;

        return $this;
    }

    public function getImageCate(): ?string
    {
        return $this->imageCate;
    }

    public function setImageCate(string $imageCate): self
    {
        $this->imageCate = $imageCate;

        return $this;
    }

    public function getDescriptionCate(): ?string
    {
        return $this->descriptionCate;
    }

    public function setDescriptionCate(?string $descriptionCate): self
    {
        $this->descriptionCate = $descriptionCate;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getNomCate();
    }

    public function getIcons(): ?string
    {
        return $this->icons;
    }

    public function setIcons(string $icons): self
    {
        $this->icons = $icons;

        return $this;
    }
}
