<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\Review;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[Route('/api/getProduct', name: 'app_product_get', methods: ['GET'])] // en izdelek fetcha po id
    public function getProduct(EntityManagerInterface $entityManager, SerializerInterface  $serializer): JsonResponse
    {
        $request = Request::createFromGlobals();
        $id = $request->query->get('id');
        $productRepository = $entityManager->getRepository(Product::class);
        $reviewRepository = $entityManager->getRepository(Review::class);
        $tagRepository = $entityManager->getRepository(Tag::class);
        $productVariantRepository = $entityManager->getRepository(ProductVariant::class);
        $product = $productRepository->getProductbyId($id);
        //$tags = $product->getTags();
        if ($product === null) {
            return new JsonResponse("Product does not exist!",404);
        }
        $productVariants = $productVariantRepository->getByProductId($id);
        foreach ($productVariants as $productVariant) {
            $product->addVariant($productVariant);
        }
       // $product->setProductVariants($productVariants);
        //$product->setReviews($reviewRepository->getByProductId($id));
        //$product_str = var_export($product, true);
        $productJson = $serializer->serialize($product, 'json');
        return JsonResponse::fromJsonString($productJson);
    }
    #[Route('/api/getProducts', name: 'app_products_get', methods: ['GET'])] // po 30 izdelkov fetcha
    public function getProducts(EntityManagerInterface $entityManager,SerializerInterface $serializer): JsonResponse
    {
        $request = Request::createFromGlobals();
        $offset = $request->query->getInt('offset', 0);
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->getProductsBy30($offset);
        $productsJson = $serializer->serialize($products, 'json');
        return JsonResponse::fromJsonString($productsJson);
    }

}
