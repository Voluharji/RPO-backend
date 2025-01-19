<?php

namespace App\Repository;

use App\Entity\ProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductVariant::class);
    }

    public function getProductVariantById(int $id): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT pv 
             FROM App\Entity\ProductVariant pv 
             WHERE pv.product_variant_id = :id'
        )
            ->setParameter('id', $id)
            ->getResult();
    }

    public function getByStock(int $stock): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT pv 
             FROM App\Entity\ProductVariant pv 
             WHERE pv.stock = :stock'
        )
            ->setParameter('stock', $stock)
            ->getResult();
    }

    public function getByColor(string $color): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT pv 
             FROM App\Entity\ProductVariant pv 
             WHERE pv.color = :color'
        )
            ->setParameter('color', $color)
            ->getResult();
    }

    public function getByProductId(int $productId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT pv 
             FROM App\Entity\ProductVariant pv 
             WHERE pv.product_id = :productId'
        )
            ->setParameter('productId', $productId)
            ->getResult();
    }

    public function getAllProductVariants(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT pv 
             FROM App\Entity\ProductVariant pv'
        )
            ->getResult();
    }
}
