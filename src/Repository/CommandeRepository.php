<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Commande $entity, bool $flush = true): void
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
    public function remove(Commande $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getId()
    {
        return $this->createQueryBuilder('c')->select('c.id')->getQuery()->getResult();
    }
    public function getSubtotal($usersId)
    {
        return $this->createQueryBuilder('c')->select('SUM(c.subTotal)')->andWhere('c.statusPaiement = 2')->andWhere('c.users = :val')->setParameter('val', $usersId)->getQuery()->getResult();
    }
    public function getQuantity($usersId)
    {
        return $this->createQueryBuilder('c')->select('SUM(c.quantite)')->andWhere('c.statusCommandes = 3')->andWhere('c.users = :val')->setParameter('val', $usersId)->getQuery()->getResult();
    }
    public function getConfirme($usersId)
    {
        return $this->createQueryBuilder('c')->orderBy('c.createdAt', 'desc')->andWhere('c.statusCommandes > 1')->andWhere('c.users = :val')->setParameter('val', $usersId)->getQuery()->getResult();
    }


    public function searchcommnade($statusL, $statusP, $code, $offest, $limit)
    {
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'desc')
            ->setFirstResult($offest)
            ->setMaxResults($limit);
        if ($statusL == "0") {
            $query = $query->andWhere('c.statusCommandes is NULL');
        } elseif ($statusL != "") {
            $query = $query->andWhere('c.statusCommandes = :val')->setParameter('val', (int) $statusL);
        }
        if ($statusP == "0") {
            $query = $query->andWhere('c.statusPaiement is NULL');
        } elseif ($statusP != "") {
            $query = $query->andWhere('c.statusPaiement = :val')->setParameter('val', (int) $statusP);
        }
        if ($code != "") {
            $query = $query->andWhere('c.codeCommande LIKE :val')->setParameter('val', '%' . $code . '%');
        }
        return $query->getQuery()->getResult();
    }
    public function countscommnade($statusL, $statusP, $code)
    {
        $query = $this->createQueryBuilder('c')->select('COUNT(c)');
        if ($statusL == "0") {
            $query = $query->andWhere('c.statusCommandes is NULL');
        } elseif ($statusL != "") {
            $query = $query->andWhere('c.statusCommandes = :val')->setParameter('val', (int) $statusL);
        }
        if ($statusP == "0") {
            $query = $query->andWhere('c.statusPaiement is NULL');
        } elseif ($statusP != "") {
            $query = $query->andWhere('c.statusPaiement = :val')->setParameter('val', (int) $statusP);
        }
        if ($code != "") {
            $query = $query->andWhere('c.codeCommande LIKE :val')->setParameter('val', '%' . $code . '%');
        }
        return $query->getQuery()->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
