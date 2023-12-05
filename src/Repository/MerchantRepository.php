<?php

namespace App\Repository;

use App\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Merchant>
 *
 * @method Merchant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merchant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merchant[]    findAll()
 * @method Merchant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merchant::class);
    }

    public function getAllNonDeletedMerchants()
    {
        return $this->createQueryBuilder('data')
                    ->andWhere('data.isDeleted = FALSE')
                    ->getQuery()
                    ->getResult();
    }

    public function getAllNonDeletedMerchantsById(int $id)
    {
        return $this->createQueryBuilder('data')
                    ->andWhere('data.id = :id')
                    ->andWhere('data.isDeleted = FALSE')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return Merchant[] Returns an array of Merchant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Merchant
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
