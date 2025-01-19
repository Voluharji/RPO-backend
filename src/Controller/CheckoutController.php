<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutController extends AbstractController
{
    #[Route('/api/checkout', name: 'app_checkout')]
    public function checkout(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        $User = new User();

        if ($request->request->get('method') == null || $request->request->get('address') == null || $request->request->get('name') == null) {
            return new JsonResponse(["missing required fields"],400);
        }
        return $this->json([
            'message' => 'Ordered successfully.'
        ],200);
    }
}
