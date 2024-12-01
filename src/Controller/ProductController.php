<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/api/get_product', name: 'get_product', methods: ['GET'])] // en izdelek fetcha po id
    public function getProduct( EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        $productRepository = $entityManager->getRepository(Product::class);
        if ($request->get('id') == null) {
            return new JsonResponse(["No product id provided."],400);
        }
        $product = $productRepository->getProductById($request->get('id'));
        if ($product == null) {
            return new JsonResponse(["Product does not exist."],404);
        }
        return $this->json(
           $product
        ,200);
    }
    #[Route('/api/getProducts', name: 'get_products', methods: ['GET'])] // po 15 izdelkov fetcha
    public function getProducts( EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        $productRepository = $entityManager->getRepository(Product::class);
        if ($request->get('id') == null) {
            return new JsonResponse(["No product id provided."],400);
        }
        $products = $productRepository->getProducts($request->get('offset'));
        if ($products == null) {
            return new JsonResponse(["Product does not exist."],404);
        }
        return $this->json(
            $products
        ,200);

    }

}
