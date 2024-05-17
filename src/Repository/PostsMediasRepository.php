<?php

namespace App\Repository;

use App\Entity\PostsMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostsMedias>
 */
class PostsMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostsMedias::class);
    }

    public function deleteByFilename($filename)
    {
        return $this->createQueryBuilder('p')
            ->delete()
            ->andWhere('p.filename = :filename')
            ->setParameter('filename', $filename)
            ->getQuery()
            ->getResult();
    }

    public function findFilenameByPost($post)
    {
        return $this->createQueryBuilder('p')
            ->select('p.filename')
            ->andWhere('p.fk_post = :post')
            ->setParameter('post', $post)
            ->getQuery()
            ->getResult();
    }

    public function removeByPost($post)
    {
        return $this->createQueryBuilder('p')
            ->delete()
            ->andWhere('p.fk_post = :post')
            ->setParameter('post', $post)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return PostsMedias[] Returns an array of PostsMedias objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PostsMedias
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
