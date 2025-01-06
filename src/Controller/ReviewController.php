<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewController extends AbstractController
{
    #[Route('/api/user/review_add', name: 'app_review_add',methods: ['POST'])]
    public function addReview(EntityManagerInterface $entityManager, Security $securityService): JsonResponse
    {
        $user = $this->getUser();

        $review = new Review();
        $reviewRepository = $entityManager->getRepository(Review::class);
        $userRepository = $entityManager->getRepository(User::class);
        $productRepository = $entityManager->getRepository(Product::class);
        //$Repository = $entityManager->getRepository(User::class);
        $request = Request::createFromGlobals();
        if ($request->request->get('description') == null || $request->request->get('rating') == null || $request->request->get('productId') == null) {
            return $this->json("invalid request", 400);
        }
        $review->setDescription($request->request->get('description'));
        $review->setRating($request->request->get('rating'));
        try {
            $product = $productRepository->getProductById($request->get("productId"));
            $userFromDb = $userRepository->getByEmail($user->getUserIdentifier()); // troll fix...
        }
        catch (Exception $ex){
            return $this->json(
                "product or user does not exist."
                ,401);
        }
        $review->setProduct($product);


        $review->setUser($userFromDb);
        //$review->setUserId($userFromDb->getUserId());

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
