<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Produit $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Produit $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */

    public function search($value, $number = null)
    {
        if ($number == '0') {
            $query = $this->createQueryBuilder('p')
                ->andWhere('p.nomPro LIKE :val')
                ->setParameter('val', '%' . $value . '%');
        } else {
            $query = $this->createQueryBuilder('p')
                ->andWhere('p.nomPro LIKE :val')
                ->setParameter('val', '%' . $value . '%')
                ->join('p.categorie', 'c')
                ->andWhere('c.id IN (:valu) ')
                ->setParameter('valu', (int)$number);
        }

        return $query->getQuery()->getResult();
    }

    public function searchPro($nomPro, $trierPro, $limit, $offest)
    {

        $query = $this->createQueryBuilder('p')->setFirstResult($offest)->setMaxResults($limit);
        if ($nomPro != "") {
            $query = $query->andWhere('p.nomPro LIKE :val')
                ->setParameter('val', '%' . $nomPro . '%');
        }
        if ($trierPro != "") {
            $query = $query->groupBy($trierPro);
        }

        //dd($query->getQuery()->getResult());
        return $query->getQuery()->getResult();
    }
    // public function countsPro($nomPro, $trierPro)
    // {

    //     $query = $this->createQueryBuilder('p')->select('COUNT(p)');
    //     if ($nomPro != "") {
    //         $query = $query->andWhere('p.nomPro LIKE :val')
    //             ->setParameter('val', '%' . $nomPro . '%');
    //     }
    //     if ($trierPro != "") {
    //         $query = $query->groupBy($trierPro);
    //     }

    //     dd($query->getQuery()->getResult());
    //     return $query->getQuery()->getResult();
    // }
    public function pagination($page, $limit)
    {

        $query = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        //dd($query->getQuery()->getResult());
        return $query->getQuery()->getResult();
    }



    public function searchwithCate($search, $offest, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'desc')
            ->setFirstResult($offest)->setMaxResults($limit);
        if ($search->getMinPrix()) {
            $query = $query->andWhere("p.prixPro > " . $search->getMinPrix());
        }
        if ($search->getMaxPrix()) {
            $query = $query->andWhere("p.prixPro < " . $search->getMaxPrix());
        }
        if ($search->getCategorie()) {
            $query = $query->join('p.categorie', 'c')
                ->andWhere('c.nomCate IN (:valu) ')
                ->setParameter('valu', $search->getCategorie());
        }
        //dd($query->getQuery()->getResult());
        return $query->getQuery()->getResult();
    }
    public function searchwithCatecount($search)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)');
        if ($search->getMinPrix()) {
            $query = $query->andWhere("p.prixPro > " . $search->getMinPrix());
        }
        if ($search->getMaxPrix()) {
            $query = $query->andWhere("p.prixPro < " . $search->getMaxPrix());
        }
        if ($search->getCategorie()) {
            $query = $query->join('p.categorie', 'c')
                ->andWhere('c.nomCate IN (:valu) ')
                ->setParameter('valu', $search->getCategorie());
        }
        //dd($query->getQuery()->getResult());

        return $query->getQuery()->getResult();
    }
}
