<?php

namespace App\Repository;

use App\Entity\ReasonForChange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ReasonForChange|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReasonForChange|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReasonForChange[]    findAll()
 * @method ReasonForChange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonForChangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReasonForChange::class);
    }

    // /**
    //  * @return ReasonForChange[] Returns an array of ReasonForChange objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReasonForChange
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
