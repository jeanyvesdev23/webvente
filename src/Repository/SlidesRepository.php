<?php

namespace App\Repository;

use App\Entity\Slides;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Slides|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slides|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slides[]    findAll()
 * @method Slides[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlidesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slides::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Slides $entity, bool $flush = true): void
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
    public function remove(Slides $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function searchSlides($value, $offest, $limit)
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.createdAt', 'desc')
            ->setFirstResult($offest)
            ->setMaxResults($limit)
            ->andWhere('s.titre LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return categorie[] Returns an array of Commentaire objects
     */

    public function countSlides($value)
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->andWhere('s.titre LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }
}
