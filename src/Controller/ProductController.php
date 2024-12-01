<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/api/getProduct', name: 'get_product', methods: ['GET'])] // en izdelek fetcha po id
    public function getProduct( EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
    #[Route('/api/getProducts', name: 'get_products', methods: ['GET'])] // po 15 izdelkov fetcha
    public function getProducts( EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

}
