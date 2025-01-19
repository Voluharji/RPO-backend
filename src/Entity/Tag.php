<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $tag_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagProduct::class, cascade: ['persist', 'remove'])]
    private Collection $tagProducts;


    public function __construct()
    {
        $this->tagProducts = new ArrayCollection();
    }

    public function getTagId(): ?int
    {
        return $this->tag_id;
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

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }
    public function getTagProducts(): Collection
    {
        return $this->tagProducts;
    }

    public function addTagProduct(TagProduct $tagProduct): static
    {
        if (!$this->tagProducts->contains($tagProduct)) {
            $this->tagProducts[] = $tagProduct;
            $tagProduct->setTag($this);
        }

        return $this;
    }

    public function removeTagProduct(TagProduct $tagProduct): static
    {
        if ($this->tagProducts->removeElement($tagProduct)) {
            if ($tagProduct->getTag() === $this) {
                $tagProduct->setTag(null);
            }
        }

        return $this;
    }
}

