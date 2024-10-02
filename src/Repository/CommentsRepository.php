<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    // Для pagerfanta
    public function createOrderedByUserQueryBuilder($value): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user_owner = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'DESC')
            ;
    }

        /**
         * @return Comments[] Returns an array of Comments objects
         */
        public function getFiveByUser($value): array
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.user_owner = :val')
                ->setParameter('val', $value)
                ->orderBy('c.created_at', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult()
            ;
        }

    public function getNextCommentNumber(Tasks $tasks): int
    {
        $lastComment = $this->createQueryBuilder('c')
            ->where('c.task = :task')
            ->setParameter('task', $tasks)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lastComment) {
            return $lastComment->getNumber() + 1;
        } else {
            return 1;
        }
    }

    //    /**
    //     * @return Comments[] Returns an array of Comments objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comments
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
