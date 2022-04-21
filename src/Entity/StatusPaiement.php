<?php

namespace App\Entity;

use App\Repository\StatusPaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusPaiementRepository::class)
 */
class StatusPaiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $isPayer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typePayer;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $style;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="statusPaiement")
     */
    private $commande;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsPayer(): ?string
    {
        return $this->isPayer;
    }

    public function setIsPayer(string $isPayer): self
    {
        $this->isPayer = $isPayer;

        return $this;
    }

    public function getTypePayer(): ?string
    {
        return $this->typePayer;
    }

    public function setTypePayer(string $typePayer): self
    {
        $this->typePayer = $typePayer;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setStatusPaiement($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getStatusPaiement() === $this) {
                $commande->setStatusPaiement(null);
            }
        }

        return $this;
    }
    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }
    public function __toString()
    {
        return $this->getIsPayer();
    }
}
