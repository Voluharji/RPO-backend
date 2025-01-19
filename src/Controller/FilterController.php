<?php

namespace App\Controller;

use App\Entity\Product;
use App\Obj\Filter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class FilterController extends AbstractController
{
    #[Route('/api/filter', name: 'app_product_filter')]
    public function productFilter(EntityManagerInterface $entityManager, SerializerInterface  $serializer): JsonResponse
    {
        $request = Request::createFromGlobals();
        $filter = new Filter();
        $filter->categories = $request->query->get('categories');
        $filter->minPrice = $request->query->get('minPrice');
        $filter->maxPrice = $request->query->get('maxPrice');
        $filter->tags = $request->query->get('tags');
        $filter->search = $request->query->get('search');
        //$filter = $request->query->get('filter');
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->searchProduct($filter);
        if ($products === null) {
            return new JsonResponse("No products found.",404);
        }
        //$product_str = var_export($product, true);
        $productsJson = $serializer->serialize($products, 'json',[
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
            AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 10
        ]);
        return JsonResponse::fromJsonString($productsJson);
    }
}
