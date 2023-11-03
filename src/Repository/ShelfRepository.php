<?php

namespace App\Repository;

use App\Entity\Shelf;
use App\Entity\Shoe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shelf>
 *
 * @method Shelf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shelf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shelf[]    findAll()
 * @method Shelf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShelfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shelf::class);
    }

    /**
     * @return [Galerie][] Returns an array of [Galerie] objects
     */
    public function findByShoe(Shoe $shoe): array
    {
        return $this->createQueryBuilder('shelf')
            ->leftJoin('shelf.shoes', 'shoes')
            ->andWhere('shoes = :shoe')
            ->setParameter('shoe', $shoe)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Shelf
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
