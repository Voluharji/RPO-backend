<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function getProductById(int $id): ?Product
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p 
         FROM App\Entity\Product p 
         WHERE p.product_id = :id'
        )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }
    public function getProductByName(string $name): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p 
         FROM App\Entity\Product p 
         WHERE p.name LIKE :name'
        )
            ->setParameter('name', '%' . $name . '%')
            ->getResult();
    }
    public function getAllProducts(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p 
         FROM App\Entity\Product p'
        )
            ->getResult();
    }
    public function createProduct(Product $product): Product
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }
    public function getProductsByCategory(int $categoryId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p 
         FROM App\Entity\Product p 
         WHERE p.category_id = :categoryId'
        )
            ->setParameter('categoryId', $categoryId)
            ->getResult();
    }
    public function getProductsBy30(int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults(30)
            ->orderBy('p.time_created', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
    public function findProductsByPriceRange(float $minPrice, float $maxPrice): array
    {
        $entityManager = $this->getEntityManager();

        $dql = '
            SELECT p
            FROM App\Entity\Product p
            WHERE p.price >= :minPrice
            AND p.price <= :maxPrice
            ORDER BY p.price ASC
        ';

        $query = $entityManager->createQuery($dql)
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice);

        return $query->getResult();
    }
    public function searchProduct(string $input): array
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT p 
                FROM App\Entity\Product p
                WHERE p.name LIKE :input';

        $query = $entityManager->createQuery($dql)
            ->setParameter('input', '%' . $input . '%');

        return $query->getResult();
    }
    public function deleteProductById(int $productId): void
    {
        $entityManager = $this->getEntityManager();

        $product = $entityManager->getRepository(Product::class)->find($productId);

        if ($product) {
            $entityManager->remove($product);
            $entityManager->flush();
        }
    }

}
