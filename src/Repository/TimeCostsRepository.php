<?php

namespace App\Repository;

use App\Entity\TimeCosts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeCosts>
 */
class TimeCostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeCosts::class);
    }

        /**
         * @return TimeCosts[] Returns an array of TimeCosts objects
         */
        public function findByTask($value): array
        {
            return $this->createQueryBuilder('t')
                ->andWhere('t.task = :val')
                ->setParameter('val', $value)
                ->orderBy('t.created_at', 'DESC')
                ->getQuery()
                ->getResult()
            ;
        }

    //    /**
    //     * @return TimeCosts[] Returns an array of TimeCosts objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TimeCosts
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
