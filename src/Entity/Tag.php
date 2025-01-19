<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $tag_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    #[ORM\JoinTable(name: 'tag_product')]
    #[ORM\JoinColumn(name: 'tag_id', referencedColumnName: 'tag_id')]
    #[ORM\InverseJoinColumn(name: 'product_id', referencedColumnName: 'product_id')]
    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'tags')]
    #[MaxDepth(1)]
    #[Ignore]
    private Collection $products;


    public function __construct()
    {
        $this->products = new ArrayCollection();
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


    public function removeProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $product->removeTag($this);
            $this->products->removeElement($product);
        }
        return $this;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addTag($this);
        }

        return $this;
    }


}

