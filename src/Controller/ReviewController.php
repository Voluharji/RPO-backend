<?php

namespace App\Controller;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewController extends AbstractController
{
    #[Route('/api/user/review_add', name: 'app_review_add',methods: ['POST'])]
    public function addReview(EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        $review = new Review();
        $reviewRepository = $entityManager->getRepository(Review::class);
        $request = Request::createFromGlobals();
        if ($request->request->get('description') == null || $request->request->get('rating') == null || $request->request->get('productId') == null) {
            return $this->json("invalid request", 400);
        }
        $review->setDescription($request->request->get('description'));
        $review->setRating($request->request->get('rating'));
        $review->setProductId($request->request->get('productId'));
        $review->setUsersId($user->getUserIdentifier());
        $reviewRepository->insertReview($review);
        return $this->json(
            "review added successfully"
        ,200);
    }
    #[Route('/api/review_get', name: 'get',methods: ['GET'])]
    public function getReviews(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $reviewRepository = $entityManager->getRepository(Review::class);
        $request = Request::createFromGlobals();
        if ($request->request->get('productId')) {
            return $this->json("missing product id", 400);
        }
        $reviews = $reviewRepository->getAllReviews();
        $reviews = $serializer->serialize($reviews, 'json');
        return JsonResponse::fromJsonString($reviews);
    }
}
