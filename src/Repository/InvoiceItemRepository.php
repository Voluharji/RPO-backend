<?php

namespace App\Repository;

use App\Entity\InvoiceItem;
use App\Entity\Invoice;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceItem::class);
    }

    public function getInvoiceItemById(int $id): ?InvoiceItem
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT ii 
             FROM App\Entity\InvoiceItem ii 
             WHERE ii.invoice_item_id = :id'
        )
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function getInvoiceItemsByInvoice(Invoice $invoice): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT ii 
             FROM App\Entity\InvoiceItem ii 
             WHERE ii.invoice_id = :invoiceId'
        )
            ->setParameter('invoiceId', $invoice->getInvoiceId())
            ->getResult();
    }

    public function getInvoiceItemsByProduct(Product $product): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT ii 
             FROM App\Entity\InvoiceItem ii 
             WHERE ii.product_id = :productId'
        )
            ->setParameter('productId', $product->getProductId())
            ->getResult();
    }

    public function createInvoiceItem(
        Invoice $invoice,
        Product $product,
        int $amount,
        ?string $billingAddress = null
    ): InvoiceItem {
        $entityManager = $this->getEntityManager();

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setInvoiceId($invoice->getInvoiceId());
        $invoiceItem->setProductId($product->getProductId());
        $invoiceItem->setAmount($amount);
        $invoiceItem->setBillingAddress($billingAddress);

        $entityManager->persist($invoiceItem);
        $entityManager->flush();

        return $invoiceItem;
    }

    public function getAllInvoiceItems(): array
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->createQuery(
            'SELECT ii 
             FROM App\Entity\InvoiceItem ii'
        )
            ->getResult();
    }
    public function getInvoiceItemsBy15(int $offset): array
    {
        $queryBuilder = $this->createQueryBuilder('ii')
            ->setFirstResult($offset)
            ->setMaxResults(15)
            ->orderBy('ii.invoice_id', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
