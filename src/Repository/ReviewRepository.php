<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getReviewById(int $id): ?Review
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT r 
             FROM App\Entity\Review r 
             WHERE r.review_id = :id'
        )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function getByRating(int $rating): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT r 
             FROM App\Entity\Review r 
             WHERE r.rating = :rating'
        )
            ->setParameter('rating', $rating)
            ->getResult();
    }

    public function getByProductId(int $productId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT r 
             FROM App\Entity\Review r 
             WHERE r.product_id = :productId'
        )
            ->setParameter('productId', $productId)
            ->getResult();
    }

    public function getByUserId(int $userId): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT r 
             FROM App\Entity\Review r 
             WHERE r.users_id = :userId'
        )
            ->setParameter('userId', $userId)
            ->getResult();
    }

    public function getAllReviews(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT r 
             FROM App\Entity\Review r'
        )
            ->getResult();
    }
    public function getReviewsBy15(int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->setFirstResult($offset)
            ->setMaxResults(15)
            ->orderBy('r.time_created', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
    public function insertReview(Review $review): void
    {
        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
    public function deleteReviewById(int $reviewId): void
    {
        $entityManager = $this->getEntityManager();

        $review = $entityManager->getRepository(Review::class)->find($reviewId);

        if ($review) {
            $entityManager->remove($review);
            $entityManager->flush();
        }
    }

}
