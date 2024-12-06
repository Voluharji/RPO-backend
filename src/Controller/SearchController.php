<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    #[Route('/product_search', name: 'app_product_search')]
    public function searchProducts(EntityManagerInterface $entityManager, SerializerInterface  $serializer): JsonResponse
    {
        $request = Request::createFromGlobals();
        $search = $request->query->get('search');
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->searchProducts($productRepository, $search);
        if ($products === null) {
            return new JsonResponse("No products found.",404);
        }
        //$product_str = var_export($product, true);
        $productJson = $serializer->serialize($products, 'json');
        return JsonResponse::fromJsonString($productJson);
    }
}
