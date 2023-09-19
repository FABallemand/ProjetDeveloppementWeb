<?php

namespace App\Repository;

use App\Entity\Cupboard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cupboard>
 *
 * @method Cupboard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cupboard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cupboard[]    findAll()
 * @method Cupboard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CupboardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cupboard::class);
    }

//    /**
//     * @return Cupboard[] Returns an array of Cupboard objects
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

//    public function findOneBySomeField($value): ?Cupboard
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
