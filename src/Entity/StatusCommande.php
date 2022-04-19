<?php

namespace App\Entity;

use App\Repository\StatusCommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusCommandeRepository::class)
 */
class StatusCommande
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
    private $statusLivraison;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statusPaiement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeLivraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typePaiement;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="statusCommandes")
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

    public function getStatusLivraison(): ?string
    {
        return $this->statusLivraison;
    }

    public function setStatusLivraison(string $statusLivraison): self
    {
        $this->statusLivraison = $statusLivraison;

        return $this;
    }

    public function getStatusPaiement(): ?bool
    {
        return $this->statusPaiement;
    }

    public function setStatusPaiement(bool $statusPaiement): self
    {
        $this->statusPaiement = $statusPaiement;

        return $this;
    }

    public function getTypeLivraison(): ?string
    {
        return $this->typeLivraison;
    }

    public function setTypeLivraison(string $typeLivraison): self
    {
        $this->typeLivraison = $typeLivraison;

        return $this;
    }

    public function getTypePaiement(): ?string
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(string $typePaiement): self
    {
        $this->typePaiement = $typePaiement;

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
            $commande->setStatusCommandes($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getStatusCommandes() === $this) {
                $commande->setStatusCommandes(null);
            }
        }

        return $this;
    }
}
