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

    public function createUser(User $user): User
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($user);
        $entityManager->flush();

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
    public function deleteUserById(int $userId): void
    {
        $entityManager = $this->getEntityManager();

        $user = $entityManager->getRepository(User::class)->find($userId);

        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }
    public function checkIfNameExists(string $username): bool
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT COUNT(u.user_id) 
                FROM App\Entity\User u 
                WHERE u.username = :username';

        $query = $entityManager->createQuery($dql)
            ->setParameter('username', $username);

        return $query->getSingleScalarResult() > 0;
    }
    public function checkIfMailExists(string $email): bool
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT COUNT(u.user_id) 
                FROM App\Entity\User u 
                WHERE u.email = :email';

        $query = $entityManager->createQuery($dql)
            ->setParameter('email', $email);

        return $query->getSingleScalarResult() > 0;
    }
    public function isAdmin(int $userId): bool
    {
        $entityManager = $this->getEntityManager();

        $dql = 'SELECT u.roles 
                FROM App\Entity\User u 
                WHERE u.user_id = :userId';

        $query = $entityManager->createQuery($dql)
            ->setParameter('userId', $userId);

        $roles = $query->getSingleScalarResult();

        return in_array('ROLE_ADMIN', json_decode($roles, true));
    }
    public function AdAddProduct(Product $product, int $adminId): ?Product
    {
        if (!$this->isAdmin($adminId)) {
            throw new \Exception('Only admins can add products.');
        }

        $entityManager = $this->getEntityManager();

        $entityManager->persist($product);
        $entityManager->flush();

        return $product;
    }
    public function AdRemoveProductById(int $productId, int $adminId): void
    {
        if (!$this->isAdmin($adminId)) {
            throw new \Exception('Only admins can remove products.');
        }

        $entityManager = $this->getEntityManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        $entityManager->remove($product);
        $entityManager->flush();
    }
    public function AdUpdateProductById(int $productId, array $updatedData, int $adminId): Product
    {
        if (!$this->isAdmin($adminId)) {
            throw new \Exception('Only admins can update products.');
        }

        $entityManager = $this->getEntityManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        // Apply updates
        if (isset($updatedData['name'])) {
            $product->setName($updatedData['name']);
        }
        if (isset($updatedData['price'])) {
            $product->setPrice($updatedData['price']);
        }
        if (isset($updatedData['category_id'])) {
            $product->setCategoryId($updatedData['category_id']);
        }
        if (isset($updatedData['description'])) {
            $product->setDescription($updatedData['description']);
        }

        $entityManager->flush();

        return $product;
    }
    public function changePassword(int $userId, string $newPassword): void
    {
        $entityManager = $this->getEntityManager();
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        $user->setPassword($newPassword);
        $entityManager->flush();
    }
    public function changeMail(int $userId, string $newEmail): void
    {
        $entityManager = $this->getEntityManager();
        $dql = 'SELECT COUNT(u) 
                FROM App\Entity\User u 
                WHERE u.email = :newEmail';
        $query = $entityManager->createQuery($dql)
            ->setParameter('newEmail', $newEmail);
        $emailExists = $query->getSingleScalarResult();

        if ($emailExists) {
            throw new \Exception('Email is already in use.');
        }

        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        // Update the email
        $user->setEmail($newEmail);
        $entityManager->flush();
    }
    public function AdDeleteReviewById(int $reviewId, bool $isAdmin): void
    {
        if (!$isAdmin) {
            throw new \Exception('Only admins can delete reviews.');
        }

        $entityManager = $this->getEntityManager();
        $review = $entityManager->getRepository(Review::class)->find($reviewId);

        if (!$review) {
            throw new \Exception('Review not found.');
        }

        $entityManager->remove($review);
        $entityManager->flush();
    }
    public function AdUpdateProductPrice(int $productId, float $newPrice, bool $isAdmin): void
    {
        if (!$isAdmin) {
            throw new \Exception('Only admins can update product prices.');
        }

        $entityManager = $this->getEntityManager();
        $product = $entityManager->getRepository(Product::class)->find($productId);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        if ($newPrice < 0) {
            throw new \Exception('Price cannot be negative.');
        }

        $product->setPrice($newPrice);

        $entityManager->flush();
    }

}
