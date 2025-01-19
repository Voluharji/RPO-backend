<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getTagById(int $id): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT t
             FROM App\Entity\Tag t
             WHERE t.tag_id = :id'
        )
            ->setParameter('id', $id)
            ->getResult();
    }

    public function getTagByName(string $name): ?Tag
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT t
             FROM App\Entity\Tag t
             WHERE t.tag_name = :name'
        )
            ->setParameter('name', $name)
            ->getOneOrNullResult();
    }

    public function getAllTags(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT t
             FROM App\Entity\Tag t'
        )
            ->getResult();
    }

    public function getProductsByTagId(int $tagId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p
             FROM App\Entity\Product p
             JOIN p.tags t
             WHERE t.tag_id = :tagId'
        )
            ->setParameter('tagId', $tagId)
            ->getResult();
    }
}
