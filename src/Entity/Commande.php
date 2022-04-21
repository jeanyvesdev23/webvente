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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commander")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="commande")
     */
    private $produits;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLivraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $information;

    /**
     * @ORM\OneToMany(targetEntity=Panier::class, mappedBy="commande", orphanRemoval=true,cascade={"persist"})
     */
    private $panier;



    /**
     * @ORM\ManyToOne(targetEntity=StatusCommande::class, inversedBy="commande")
     */
    private $statusCommandes;

    /**
     * @ORM\ManyToOne(targetEntity=StatusPaiement::class, inversedBy="commande")
     */
    private $statusPaiement;



    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->panier = new ArrayCollection();
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





    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

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
            $produit->setCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCommande() === $this) {
                $produit->setCommande(null);
            }
        }

        return $this;
    }

    public function getAddressLivraison(): ?string
    {
        return $this->addressLivraison;
    }

    public function setAddressLivraison(string $addressLivraison): self
    {
        $this->addressLivraison = $addressLivraison;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPanier(): Collection
    {
        return $this->panier;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->panier->contains($panier)) {
            $this->panier[] = $panier;
            $panier->setCommande($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->panier->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getCommande() === $this) {
                $panier->setCommande(null);
            }
        }

        return $this;
    }



    public function getStatusCommandes(): ?StatusCommande
    {
        return $this->statusCommandes;
    }

    public function setStatusCommandes(?StatusCommande $statusCommandes): self
    {
        $this->statusCommandes = $statusCommandes;

        return $this;
    }
    public function getStatusPaiement(): ?StatusPaiement
    {
        return $this->statusPaiement;
    }

    public function setStatusPaiement(?StatusPaiement $statusPaiement): self
    {
        $this->statusPaiement = $statusPaiement;

        return $this;
    }
}
