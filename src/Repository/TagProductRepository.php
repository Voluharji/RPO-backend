<?php

namespace App\Repository;

use App\Entity\TagProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TagProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagProduct::class);
    }

    public function getByProductId(int $productId): ?TagProduct
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT tp 
             FROM App\Entity\TagProduct tp 
             WHERE tp.product_id = :productId'
        )
            ->setParameter('productId', $productId)
            ->getOneOrNullResult();
    }

    public function getByTagId(int $tagId): ?TagProduct
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT tp 
             FROM App\Entity\TagProduct tp 
             WHERE tp.tag_id = :tagId'
        )
            ->setParameter('tagId', $tagId)
            ->getOneOrNullResult();
    }

    public function getAllByProductId(int $productId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT tp 
             FROM App\Entity\TagProduct tp 
             WHERE tp.product_id = :productId'
        )
            ->setParameter('productId', $productId)
            ->getResult();
    }

    public function getAllByTagId(int $tagId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT tp 
             FROM App\Entity\TagProduct tp 
             WHERE tp.tag_id = :tagId'
        )
            ->setParameter('tagId', $tagId)
            ->getResult();
    }

    public function getAllTagProducts(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT tp 
             FROM App\Entity\TagProduct tp'
        )
            ->getResult();
    }
}
