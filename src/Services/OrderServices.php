<?php

namespace App\Services;

use App\Entity\Commande;
use App\Entity\Panier;
use App\Entity\StatusCommande;
use Doctrine\ORM\EntityManagerInterface;

class OrderServices
{
    protected $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function savecart($data, $user)
    {

        $commande = new Commande;
        $codeCommande = (int) substr(uniqid(mt_rand()), 0, 8);
        
        $address = $data["checkout"]["Address"];
        $information = $data["checkout"]["Information"];
        $commande->setCodeCommande(new \DateTime)->setSubTotal($data["data"]["total"])->setQuantite($data["data"]["totalqte"])->setUsers($user)->setAddressLivraison($address)->setInformation($information);
        foreach ($data["produit"] as $produit) {
            $panier = new Panier;
            $total = $produit['quantite'] * $produit['produit']->getPrixPro();
            $panier->setCommande($commande)
                ->setImageProduct($produit["produit"]->getImagePro())
                ->setNameProduct($produit['produit']->getNomPro())
                ->setPriceProduct($produit["produit"]->getPrixPro())
                ->setDescriptionProduct($produit["produit"]->getDescriptionPro())
                ->setQteProduct($produit['quantite'])
                ->setTotal($total);

            $this->em->persist($panier);
            $this->em->flush();
        }

        return $data;
    }
}
