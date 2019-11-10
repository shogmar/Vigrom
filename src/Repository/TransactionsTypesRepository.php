<?php

namespace App\Repository;

use App\Entity\TransactionsTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TransactionsTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionsTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionsTypes[]    findAll()
 * @method TransactionsTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionsTypes::class);
    }

    // /**
    //  * @return TransactionsTypes[] Returns an array of TransactionsTypes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransactionsTypes
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
