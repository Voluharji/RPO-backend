<?php

namespace App\Entity;

use App\Repository\ProductVariantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductVariantRepository::class)]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $product_variant_id = null;
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $color = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 5)]
    private ?string $size = null;

    #[ORM\Column]
    private ?int $product_id = null;
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'variants')]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id", nullable: false)]
    private Product $product;
    public function getProductVariantId(): ?int
    {
        return $this->product_variant_id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

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
    public function getProduct(): Product
    {
        return $this->product;
    }
    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
