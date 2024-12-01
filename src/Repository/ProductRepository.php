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

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    /**
     * @return Product[]
     */
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
    public function createProduct(
        string $name,
        float $price,
        Category $category,
        string $description = "Sample description"
    ): Product {
        $entityManager = $this->getEntityManager();

        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setCategoryId($category->getCategoryId());
        $product->setDescription($description);
        $product->setTimeCreated(new \DateTime());

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



    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
