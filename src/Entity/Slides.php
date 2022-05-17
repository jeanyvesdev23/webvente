<?php

namespace App\Entity;

use App\Entity\Traits\timestandable;
use App\Repository\SlidesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SlidesRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Slides
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sousTitre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageSlides;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSousTitre(): ?string
    {
        return $this->sousTitre;
    }

    public function setSousTitre(string $sousTitre): self
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    public function getImageSlides(): ?string
    {
        return $this->imageSlides;
    }

    public function setImageSlides(string $imageSlides): self
    {
        $this->imageSlides = $imageSlides;

        return $this;
    }
}
