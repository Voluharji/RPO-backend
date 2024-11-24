<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $invoice_id = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timeCreated = null;

    #[ORM\Column]
    private ?int $users_id = null;
    #[ORM\ManyToOne(targetEntity: "Users")]
    #[ORM\JoinColumn(name: "users_id", referencedColumnName: "users_id")]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceId(): ?int
    {
        return $this->invoice_id;
    }
    public function getTimeCreated(): ?\DateTimeInterface
    {
        return $this->timeCreated;
    }

    public function setTimeCreated(\DateTimeInterface $timeCreated): static
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    public function getUsersId(): ?int
    {
        return $this->users_id;
    }

    public function setUsersId(int $users_id): static
    {
        $this->users_id = $users_id;

        return $this;
    }
}
