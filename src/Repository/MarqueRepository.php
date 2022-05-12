<?php

namespace App\Repository;

use App\Entity\Marque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Marque|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marque|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marque[]    findAll()
 * @method Marque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Marque::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Marque $entity, bool $flush = true): void
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
    public function remove(Marque $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function searchMarque($value, $offest, $limit)
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'desc')
            ->setFirstResult($offest)
            ->setMaxResults($limit)
            ->andWhere('m.nomMarque LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return marque[] Returns an array of Commentaire objects
     */

    public function countMarque($value, $offest, $limit)
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m)')
            ->orderBy("m.createdAt", "desc")
            ->setFirstResult($offest)
            ->setMaxResults($limit)
            ->andWhere('m.nomMarque LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }
}
