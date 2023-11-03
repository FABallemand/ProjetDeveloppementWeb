<?php

namespace App\Repository;

use App\Entity\Shoe;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shoes>
 *
 * @method Shoe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shoe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shoe[]    findAll()
 * @method Shoe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shoe::class);
    }

    public function save(Shoe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shoe $entity, bool $flush = false): void
    {
        $shelfRepository = $this->getEntityManager()->getRepository(Shelf::class);

        // get rid of the ManyToMany relations with shelves
        $shelves = $shelfRepository->findByShoe($entity);
        foreach ($shelves as $shelf) {
            $shelf->removeShoe($entity);
            $this->getEntityManager()->persist($shelf);
        }
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Shoe[] Returns an array of Shoe objects
     */
    public function findByMember(Member $member): array
    {
        return $this->createQueryBuilder('shoe')
            ->leftJoin('shoe.cupboard', 'cupboard')
            ->andWhere('cupboard.member = :member')
            ->setParameter('member', $member)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Shoe
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
