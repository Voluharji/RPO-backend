<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LogoutController extends AbstractController // troll controller, tak al tak se bodo na frontendu logoutnili.
{
    #[Route('/api/logout', name: 'app_user_logout')]
    public function logout(EntityManagerInterface $entityManager): JsonResponse{
        $request = Request::createFromGlobals();
        $repository = $entityManager->getRepository(User::class);
        $response = $repository->GetUserByUsernameOrEmail($request->get("username"));
        if ($response === null)
            $response = "No user found";

        $debug = var_export($response, true);
        return $this->json($debug, 200);

    }
}
