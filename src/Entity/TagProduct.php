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
    private ?int $product_id = null;
    #[ORM\Column]
    private ?int $tag_id = null;
    #[ORM\ManyToOne(targetEntity: "Product")]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id")]
    private Product $product;
    #[ORM\ManyToOne(targetEntity: "Tag")]
    #[ORM\JoinColumn(name: "tag_id", referencedColumnName: "tag_id")]
    private Tag $tag;
    public function getProductId(): ?int
    {
        return $this->product_id;
    }
    public function getTagId(): ?int
    {
        return $this->tag_id;
    }

    public function setTagId(int $tag_id): static
    {
        $this->tag_id = $tag_id;

        return $this;
    }
}
