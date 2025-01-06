<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FilterController extends AbstractController
{
    #[Route('/api/filter', name: 'app_product_filter')]
    public function productFilter(EntityManagerInterface $entityManager, SerializerInterface  $serializer): JsonResponse
    {
        $request = Request::createFromGlobals();
        $filter = $request->query->get('filter');
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->searchProduct($filter);
        if ($products === null) {
            return new JsonResponse("No products found.",404);
        }
        //$product_str = var_export($product, true);
        $productsJson = $serializer->serialize($products, 'json');
        return JsonResponse::fromJsonString($productsJson);
    }
}
