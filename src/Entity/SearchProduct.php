<?php

namespace App\Entity;

use App\Repository\SearchProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SearchProductRepository::class)
 */
class SearchProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $categorie;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $minPrix;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $maxPrix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMinPrix(): ?float
    {
        return $this->minPrix;
    }

    public function setMinPrix(float $minPrix): self
    {
        $this->minPrix = $minPrix;

        return $this;
    }

    public function getMaxPrix(): ?float
    {
        return $this->maxPrix;
    }

    public function setMaxPrix(float $maxPrix): self
    {
        $this->maxPrix = $maxPrix;

        return $this;
    }
}
