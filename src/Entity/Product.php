<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $product_id = null;
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $category_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $time_created = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;
    #[ORM\ManyToOne(targetEntity: "Category")]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "category_id")]
    #[MaxDepth(1)]
    private ?Category $category = null;
    #[ORM\OneToMany(targetEntity: ProductVariant::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    #[MaxDepth(1)]
    private Collection $variants;
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'products')]
    #[MaxDepth(1)]
    private Collection $tags;
    public function __construct()
    {
        $this->variants = new ArrayCollection();
    }
    public function getProductId(): ?int
    {
        return $this->product_id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): static
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getTimeCreated(): ?\DateTimeInterface
    {
        return $this->time_created;
    }

    public function setTimeCreated(\DateTimeInterface $time_created): static
    {
        $this->time_created = $time_created;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function getVariants(): Collection
    {
        return $this->variants;
    }
    public function getTags(): Collection
    {
        return $this->tags;
    }
    public function setTags(Collection $tags): Collection
    {
        $this->tags = $tags;
        return $this->tags;
    }
    public function addVariant(ProductVariant $variant): static
    {
        if (!$this->variants->contains($variant)) {
            $this->variants[] = $variant;
            $variant->setProduct($this);
        }

        return $this;
    }
    public function setProductVariants(Collection $variants): static
    {
        $this->variants = $variants;
        return $this;
    }
    public function removeVariant(ProductVariant $variant): static
    {
        if ($this->variants->removeElement($variant)) {
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }

        return $this;
    }
    public function getProductVariants(): Collection
    {
        return $this->variants;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tagProducts->contains($tag)) {
            $this->tagProducts[] = $tag;
            $tag->addProduct($this);
        }

        return $this;
    }

    public function removeTag(Tag $tags): static
    {
        if ($this->tags->removeElement($tags)) {
            if ($tags->getProducts()->contains($this)) {
                $tags->removeProduct($this);
            }
        }

        return $this;
    }
}
