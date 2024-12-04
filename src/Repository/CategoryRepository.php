<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getCategoryById(int $id): ?Category
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT c 
             FROM App\Entity\Category c 
             WHERE c.category_id = :id'
        )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function getCategoryByParent(?int $parentId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT c 
             FROM App\Entity\Category c 
             WHERE c.parent_category_id = :parentId'
        )
            ->setParameter('parentId', $parentId)
            ->getResult();
    }

    public function getCategoryByName(string $name): ?Category
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT c 
             FROM App\Entity\Category c 
             WHERE c.category_name = :name'
        )
            ->setParameter('name', $name)
            ->getOneOrNullResult();
    }

    public function getAllCategories(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT c 
             FROM App\Entity\Category c'
        )
            ->getResult();
    }

    public function createCategory(string $name, ?Category $parentCategory = null): Category
    {
        $entityManager = $this->getEntityManager();

        $category = new Category();
        $category->setCategoryName($name);
        $category->setParentCategory($parentCategory);

        $entityManager->persist($category);
        $entityManager->flush();

        return $category;
    }
    public function getCategoriesBy15(int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults(15)
            ->orderBy('c.name', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
