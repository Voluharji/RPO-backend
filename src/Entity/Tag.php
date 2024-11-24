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
    private ?string $tag_name = null;
    #[ORM\ManyToMany(targetEntity: "Product", mappedBy: "tags")]
    private Collection $products;
    public function __construct()
    {
        $this->products=new ArrayCollection();
    }
    public function getTagId(): ?int
    {
        return $this->tag_id;
    }
    public function getTagName(): ?string
    {
        return $this->tag_name;
    }

    public function setTagName(string $tag_name): static
    {
        $this->tag_name = $tag_name;

        return $this;
    }
}
