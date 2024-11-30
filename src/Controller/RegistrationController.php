<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'register', methods: ['POST'])]
    public function register(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $request = Request::createFromGlobals();
        $User = new User();
        if ($request->request->get('email') == null || $request->request->get('password') == null || $request->request->get('username') == null) {
            return new JsonResponse(["missing required fields"],400);
        }
        $User->setEmail($request->request->get('email'));
        $User->setFirstName($request->request->get('firstName'));
        $User->setLastName($request->request->get('lastName'));
        $User->setPhoneNumber($request->request->get('phoneNumber'));
        $User->setTimeCreated(new \DateTime());
        $passwordHasher->hashPassword($User, $request->request->get('password'));

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RegistrationController.php',
        ]);
    }
}
