<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Base;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Config\DoctrineConfig;

class UserController extends AbstractController
{

    #[Route('/api/user/get_user_data', name: 'app_user_get_data', methods: ['GET'])]
    public function getUserData(EntityManagerInterface $entityManager): JsonResponse{
        $request = Request::createFromGlobals();
        $repository = $entityManager->getRepository(User::class);
        if ($request->get("id") === null) {
            return new JsonResponse("No user id provided",400);
        }
        $response = $repository->GetUserById($request->get("id"));
        if ($response === null)
            return new JsonResponse("No user found by id",400);

        //$debug = var_export($response, true);
        return $this->json(Serializer::class->serialize($response,'json'), 200);
    }
    #[Route('/api/user/update_user_data', name: 'app_user_change_data', methods: ['POST'])]
    public function updateUserData(EntityManagerInterface $entityManager) : JsonResponse{
        $request = Request::createFromGlobals();
        $repository = $entityManager->getRepository(User::class);
        if ($request->get("id") === null) {
            return new JsonResponse("No user id provided",400);
        }
        $user= $this->getUser();
        if ($request->get("username") != Null) {
            if ($repository->loadUserByIdentifier($request->get("username")) !== null) {
                return new JsonResponse("Username is already taken!",400);
            }
            $user->setUsername($request->get("username"));
        }
        if ($request->get("email") != Null) {
            if ($repository->loadUserByIdentifier($request->get("email")) !== null) {
                return new JsonResponse("Email is already taken!",400);
            }
            $user->setEmail($request->get("username"));
        }
        if ($request->get("firstName") != Null) {
            $user->setLastName($request->get("firstName"));
        }
        if ($request->get("lastName") != Null) {
            $user->setLastName($request->get("lastName"));
        }
        if ($request->get("phoneNumber") != Null) {
            $user->setPhoneNumber($request->get("phoneNumber"));
        }
      $repository->updateUser($user);
      return $this->json("Succesfully updated user data.", 200);
    }
    #[Route('/api/admin/update_user_data', name: 'app_user_change_data', methods: ['POST'])]
    public function updateUserDataAdmin(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher) : JsonResponse{
        $request = Request::createFromGlobals();
        $UserRepository = $entityManager->getRepository(User::class);
        if ($request->get("id") === null) {
            return new JsonResponse("No user id provided",400);
        }
        $user= $UserRepository->getUserById($request->get("id"));
        if ($request->get("username") != Null) {
            if ($UserRepository->loadUserByIdentifier($request->get("username")) !== null) {
                return new JsonResponse("Username is already taken!",400);
            }
            $user->setUsername($request->get("username"));
        }
        if ($request->get("email") != Null) {
            if ($UserRepository->getUserByEmail($request->get("email")) !== null) {
                return new JsonResponse("Email is already taken!",400);
            }
            $user->setEmail($request->get("username"));
        }
        if ($request->get("firstName") != Null) {
            $user->setLastName($request->get("firstName"));
        }
        if ($request->get("lastName") != Null) {
            $user->setLastName($request->get("lastName"));
        }
        if ($request->get("phoneNumber") != Null) {
            $user->setPhoneNumber($request->get("phoneNumber"));
        }
        if ($request->get("password") != Null) {
            $user->setPhoneNumber($request->get("phoneNumber"));
        }

        $hashedPassword = $passwordHasher->hashPassword ($user, $request->request->get('password'));
        $UserRepository->updateUser($user);
        return $this->json("Succesfully updated user data.", 200);
    }

}
