<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class UserController extends AbstractController
{

    #[Route('/webapi/login', name: 'app_login')]
    public function index(): JsonResponse
    {
        $repository = $entityManager->getRepository(Product::class);
        $request = Request::createFromGlobals();
        $request->get("id");

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
