<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Base;
use mysql_xdevapi\Exception;
use Symfony\Component\Filesystem\Filesystem;
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
        $userRepository = $entityManager->getRepository(User::class);
        $user = $this->getUser();
        try {
            $userFromDb = $userRepository->getByEmail($user->getUserIdentifier()); // troll fix...
        }
        catch (Exception $ex){
            return $this->json(
                "user does not exist."
                ,401);
        }
        $userData = array(
            "username" => $userFromDb->getUsername(),
            "firstName" => $userFromDb->getFirstName(),
            "lastName" => $userFromDb->getLastName(),
            "email" => $userFromDb->getEmail(),
            "phoneNumber" => $userFromDb->getPhoneNumber(),
            "timeCreated" => $userFromDb->getTimeCreated(),
            "imgRef" => $userFromDb->getImgRef(),
        );
        //$debug = var_export($response, true);
        return $this->json($userData, 200);
    }
    #[Route('/api/user/update_user_data', name: 'app_user_change_data', methods: ['POST'])]
    public function updateUserData(EntityManagerInterface $entityManager) : JsonResponse{
        $request = Request::createFromGlobals();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $this->getUser();
        try {
            $userFromDb = $userRepository->getByEmail($user->getUserIdentifier()); // troll fix...
        }
        catch (Exception $ex){
            return $this->json(
                "user does not exist."
                ,401);
        }
        if ($request->get("username") != Null) {
            if ($userRepository->getUserByUsername($request->get("username")) !== null) {
                return $this->json(
                    "Username is already taken!"
                    ,400);
            }
            $userFromDb->setUsername($request->get("username"));
        }
        if ($request->get("email") != Null) {
            if ($userRepository->getByEmail($request->get("email")) !== null) {
                return new JsonResponse("Email is already taken!",400);
            }
            $userFromDb->setEmail($request->get("email"));
        }
        if ($request->get("firstName") != Null) {
            $userFromDb->setLastName($request->get("firstName"));
        }
        if ($request->get("lastName") != Null) {
            $userFromDb->setLastName($request->get("lastName"));
        }
        if ($request->get("phoneNumber") != Null && is_numeric($request->get("phoneNumber"))) {
            $userFromDb->setPhoneNumber($request->get("phoneNumber"));
        }
        $userRepository->updateUser($userFromDb);
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
    #[Route('/api/user/update_profile_picture', name: 'app_user_change_data', methods: ['POST'])]
    public function updateProfilePicture(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher) : JsonResponse{
        $location = "/public/assets/images/profile_pictures/";
        $request = Request::createFromGlobals();
        $UserRepository = $entityManager->getRepository(User::class);
        $request = Request::createFromGlobals();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $this->getUser();
        try {
            $userFromDb = $userRepository->getByEmail($user->getUserIdentifier()); // troll fix...
        }
        catch (Exception $ex){
            return $this->json(
                "user does not exist."
                ,401);
        }
        if ($request->get("id") === null) {
            return new JsonResponse("No user id provided",400);
        }
        $file = $request->files->get('profile_picture');
        $nameId = $userFromDb->getUserId();
        $filesystem = new Filesystem();
        $user = $UserRepository->getUserById($request->get("id"));


        return $this->json("Succesfully updated user data.", 200);
    }
}
