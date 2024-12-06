<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    public function loadUserByIdentifier(string $usernameOrEmail): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :query
                OR u.email = :query'
        )
            ->setParameter('query', $usernameOrEmail)
            ->getOneOrNullResult();
    }
    public function register(User $user): int
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return $user->getUserId();
    }
    public function getByEmail(string $email): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\User u
             WHERE u.email = :email'
        )
            ->setParameter('email', $email)
            ->getOneOrNullResult();
    }

    public function getByNameAndLastName(string $firstName, string $lastName): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\User u
             WHERE u.first_name = :firstName AND u.last_name = :lastName'
        )
            ->setParameter('firstName', $firstName)
            ->setParameter('lastName', $lastName)
            ->getOneOrNullResult();
    }

    public function getByPhoneNumber(int $phoneNumber): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\User u
             WHERE u.phone_number = :phoneNumber'
        )
            ->setParameter('phoneNumber', $phoneNumber)
            ->getOneOrNullResult();
    }

    public function createUser(
        string $email,
        string $username,
        array $roles,
        string $password,
        ?string $firstName = null,
        ?string $lastName = null,
        ?int $phoneNumber = null
    ): User {
        $user = new User();
        $user->setEmail($email)
            ->setUsername($username)
            ->setRoles($roles)
            ->setPassword($password)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhoneNumber($phoneNumber)
            ->setTimeCreated(new \DateTime());
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    public function getAllUsers(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\User u'
        )
            ->getResult();
    }
    public function login(string $username): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
             FROM App\Entity\User u
             WHERE u.username = :username OR u.email = :username'
        )
            ->setParameter('username', $username)
            ->getOneOrNullResult();
    }
    public function getUsersBy15(int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->setFirstResult($offset)
            ->setMaxResults(15)
            ->orderBy('u.time_created', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
    public function getUserByUsername(string $username): ?User
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u 
            FROM App\Entity\User u
            WHERE u.username = :username'
        )->setParameter('username', $username);

        return $query->getOneOrNullResult();
    }
    public function getUserById(int $id): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT p 
         FROM App\Entity\User u 
         WHERE u.product_id = :id'
        )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }
    public function updateUser(User $user): bool
    {
        $entityManager = $this->getEntityManager();

        $existingUser = $entityManager->createQuery(
            'SELECT u 
            FROM App\Entity\User u
            WHERE u.username = :username AND u.user_id != :userId'
        )
            ->setParameter('username', $user->getUsername())
            ->setParameter('userId', $user->getUserId())
            ->getOneOrNullResult();

        if ($existingUser) {
            return false;
        }
        $existingUser = $this->find($user->getUserId());
        if (!$existingUser) {
            return false;
        }
        $existingUser->setUsername($user->getUsername());
        $existingUser->setFirstName($user->getFirstName());
        $existingUser->setLastName($user->getLastName());
        $existingUser->setPhoneNumber($user->getPhoneNumber());

        $entityManager->persist($existingUser);
        $entityManager->flush();

        return true;
    }
}
