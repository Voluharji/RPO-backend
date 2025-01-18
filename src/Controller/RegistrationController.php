<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class RegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'app_user_register', methods: ['POST'])]
    public function register(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        $User = new User();

        if ($request->request->get('email') == null || $request->request->get('password') == null || $request->request->get('username') == null) {
            return new JsonResponse(["missing required fields"],400);
        }
        if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)){
            return new JsonResponse(["invalid email address"],400);
        }
        $hashedPassword = $passwordHasher->hashPassword ($User, $request->request->get('password'));

        $User->setEmail($request->request->get('email'));
        $User->setUsername($request->request->get('username'));
        $User->setFirstName($request->request->get('firstName'));
        $User->setLastName($request->request->get('lastName'));
        $User->setPhoneNumber($request->request->get('phoneNumber'));
        $User->setTimeCreated(new \DateTime());
        $User->setPassword($hashedPassword);
        $User->setRoles(["ROLE_USER"]);
        $userRepository = $entityManager->getRepository(User::class);
        $userRepository->register($User);

        return $this->json([
            'message' => 'User registered successfully.'
        ],200);
    }
    #[Route('/api/username_check', name: 'app_username_check', methods: ['POST'])]
    public function usernameCheck(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        $User = new User();


        $User->setUsername($request->request->get('username'));
        $userRepository = $entityManager->getRepository(User::class);
        $user =  $userRepository->getUserByUsername($request->request->get('username'));
        if (isset($user->username))
            return $this->json([
                'message' => 'Username is already taken.'
            ],409);
        return $this->json([
            'message' => 'Username is available.'
        ],200);
    }
    #[Route('/api/email_check', name: 'app_email_check', methods: ['POST'])]
    public function emailCheck(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        if ($request->request->get('email') == null)
            return $this->json([
                'message' => 'Email is missing.' . $request->request->get('email')
            ],400);
        $userRepository = $entityManager->getRepository(User::class);
        $user =  $userRepository->getByEmail($request->request->get('email'));
        if (isset($user->username))
            return $this->json([
                'message' => 'Email is already taken.'
            ],409);
        return $this->json([
            'message' => 'Email is available'
        ],200);
    }
}
