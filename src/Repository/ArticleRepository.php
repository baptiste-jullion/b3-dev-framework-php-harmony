<?php

namespace App\Repository;


use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
    public function findAllPublished(): array
    {
        return $this->createQueryBuilder('a')
                    ->andWhere('a.publishedAt IS NOT NULL')
                    ->orderBy('a.publishedAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
