<?php

namespace App\Entity;

use App\Repository\InvoiceItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $invoice_item_id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $amount = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $billing_address = null;

    #[ORM\Column]
    private ?int $invoice_id = null;

    #[ORM\Column]
    private ?int $product_id = null;
    #[ORM\ManyToOne(targetEntity: 'Class:Invoice', inversedBy: 'items')]
    private Invoice $invoice;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceItemId(): ?int
    {
        return $this->invoice_item_id;
    }

    public function setInvoiceItemId(int $invoice_item_id): static
    {
        $this->invoice_item_id = $invoice_item_id;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billing_address;
    }

    public function setBillingAddress(?string $billing_address): static
    {
        $this->billing_address = $billing_address;

        return $this;
    }

    public function getInvoiceId(): ?int
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(int $invoice_id): static
    {
        $this->invoice_id = $invoice_id;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): static
    {
        $this->product_id = $product_id;

        return $this;
    }
}
