<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $category_id = null;
    #[ORM\Column(length: 255)]
    private ?string $category_name = null;

    #[ORM\Column]
    private ?int $parent_category_id = null;
    #[ORM\ManyToOne(targetEntity: "Category")]
    #[ORM\JoinColumn(name: "parent_category_id", referencedColumnName: "category_id")]
    private ?Category $parentCategory;
    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }
    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): static
    {
        $this->category_name = $category_name;

        return $this;
    }

    public function getParentCategoryId(): ?int
    {
        return $this->parent_category_id;
    }

    public function setParentCategoryId(int $parent_category_id): static
    {
        $this->parent_category_id = $parent_category_id;

        return $this;
    }
}
