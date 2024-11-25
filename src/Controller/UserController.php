<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UsersRepository;
use App\Entity\Users;
use Symfony\Config\DoctrineConfig;

class UserController extends AbstractController
{

    #[Route('/api/test', name: 'test')]
    public function test(EntityManagerInterface $entityManager): JsonResponse{
        $request = Request::createFromGlobals();
        $repository = $entityManager->getRepository(Users::class);
        $response = $repository->GetUserByUsernameOrEmail($request->get("username"));
        if ($response === null)
            $response = "No user found";

        $debug = var_export($response, true);
        return $this->json($debug, 200);

    }

    #[Route('/api/login', name: 'login')]
    public function login(EntityManagerInterface $entityManager): JsonResponse{
        $request = Request::createFromGlobals();
        $repository = $entityManager->getRepository(Users::class);
        $response = $repository->GetUserByUsernameOrEmail($request->get("username"));
        if ($response === null)
            $response = "No user found";

        $debug = var_export($response, true);
        return $this->json($debug, 200);

    }
    #[Route('/api/login_check', name: 'login_check')]
    public function login_check(EntityManagerInterface $entityManager): JsonResponse{
        $request = Request::createFromGlobals();
        $repository = $entityManager->getRepository(Users::class);
        $response = $repository->GetUserByUsernameOrEmail($request->get("username"));
        if ($response === null)
            $response = "No user found";

        $debug = var_export($response, true);
        return $this->json($debug, 200);

    }
}
