<?php

namespace App\Repository;

use App\Entity\Ham;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ham>
 *
 * @method Ham|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ham|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ham[]    findAll()
 * @method Ham[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ham::class);
    }

    //    /**
    //     * @return Ham[] Returns an array of Ham objects
    //     */
public function findByExampleField($value): array
{
    return $this->createQueryBuilder('h')
        ->andWhere('h.exampleField = :val')
        ->setParameter('val', $value)
        ->orderBy('h.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
}

    //    public function findOneBySomeField($value): ?Ham
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
