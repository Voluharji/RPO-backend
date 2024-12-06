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
    public function loadUserByIdentifier(string $identifier): ?User
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :query
                OR u.email = :query'
        )
            ->setParameter('query', $identifier)
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
            ->setTimeCreated(new \DateTime()); // Set the current date and time

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
}
