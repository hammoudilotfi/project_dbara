<?php

namespace App\Repository;

use App\Entity\Subcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;
/**
 * @extends ServiceEntityRepository<Subcategory>
 *
 * @method Subcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subcategory[]    findAll()
 * @method Subcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subcategory::class);
    }

    public function save(Subcategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subcategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findSubcategoriesByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('subcategory')
            ->where('subcategory.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }
    public function getCategoryBySubcategoryId(int $subcategoryId)
    {
        $subcategory = $this->createQueryBuilder('subcategory')
            ->where('subcategory.id = :subcategoryId')
            ->setParameter('subcategoryId', $subcategoryId)
            ->getQuery()
            ->getOneOrNullResult();
        if ($subcategory) {
            return $subcategory->getCategory();
        }
        return null;
    }

//    /**
//     * @return Subcategory[] Returns an array of Subcategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Subcategory
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
