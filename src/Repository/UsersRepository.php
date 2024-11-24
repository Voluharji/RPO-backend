<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function GetUserById($id): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT * FROM users WHERE id = :id'
        )->setParameter(':id', $id);

        return $query->getResult();
    }
    public function GetUserByUsernameOrEmail(string $username): array // samo za uporabnika
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u 
                 FROM App\Entity\Users u
                 WHERE u.username = :username or u.email = :email'
        )->setParameter(':username', $username)->setParameter(':email', $username);

        return $query->getResult();
    }
   /* public function UpdateUser(Users $user, int $id): int // samo za uporabnika
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'UPDATE users
                 SET username = :username,
                 password = :password,
                 email = :email,
                 firstName = :firstName,
                 lastName = :lastName,
                 time_created = :time_created,
                 phone = :phone
                 WHERE users.id = :id'
        )->setParameter(':username', $username)->setParameter(':email', $username)->;

        // returns an array of Product objects
        return $query->getSingleResult(); // vrne id spremenjenga objekta
    }*/
}
