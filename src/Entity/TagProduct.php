<?php

namespace App\Entity;

use App\Repository\TagProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagProductRepository::class)]
class TagProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $tagproduct_id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'tagProducts')]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id", onDelete: "CASCADE")]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'tagProducts')]
    #[ORM\JoinColumn(name: "tag_id", referencedColumnName: "tag_id", onDelete: "CASCADE")]
    private Tag $tag;

    public function getTagproductId(): ?int
    {
        return $this->tagproduct_id;
    }

    public function setTagproductId(int $tagproduct_id): static
    {
        $this->tagproduct_id = $tagproduct_id;
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

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag): static
    {
        $this->tag = $tag;
        return $this;
    }

}
