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
        $this->getEntityManager()->persist($review);
        $this->getEntityManager()->flush();
        return;

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
    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        $this->user_id = $user->getUserId(); // Sync the user ID

        return $this;
    }
    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;
        $this->product_id = $product->getProductId(); // Sync the product ID

        return $this;
    }
}
