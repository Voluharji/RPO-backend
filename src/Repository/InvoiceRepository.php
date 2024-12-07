<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT i 
             FROM App\Entity\Invoice i 
             WHERE i.invoice_id = :id'
        )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function getInvoicesByUser(User $user): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT i 
             FROM App\Entity\Invoice i 
             WHERE i.users_id = :userId'
        )
            ->setParameter('userId', $user->getUsersId())
            ->getResult();
    }

    public function getInvoicesByTimeCreated(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT i 
             FROM App\Entity\Invoice i 
             WHERE i.timeCreated BETWEEN :start AND :end'
        )
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getResult();
    }

    public function createInvoice(User $user): Invoice
{
    $entityManager = $this->getEntityManager();

    $invoice = new Invoice();
    $invoice->setUsersId($user->getUsersId());
    $invoice->setTimeCreated(new \DateTime());

    $entityManager->persist($invoice);
    $entityManager->flush();

    return $invoice;
}

    public function getAllInvoices(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT i 
             FROM App\Entity\Invoice i'
        )
            ->getResult();
    }
    public function getInvoicesBy15(int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->setFirstResult($offset)
            ->setMaxResults(15)
            ->orderBy('i.timeCreated', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
    public function deleteInvoiceById(int $invoiceId): void
    {
        $entityManager = $this->getEntityManager();

        $invoice = $entityManager->getRepository(Invoice::class)->find($invoiceId);

        if ($invoice) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }
    }

}
