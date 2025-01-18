<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    public function __construct() {
        $this->children = new ArrayCollection();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $category_id = null;
    #[ORM\Column(length: 255)]
    private ?string $category_name = null;

    #[ORM\Column]
    private ?int $parent_category_id = null;
    #[ORM\ManyToOne(targetEntity: Category::class,inversedBy: "children")]
    #[ORM\JoinColumn(name: "parent_category_id", referencedColumnName: "category_id")]
    private Category|null $parentCategory = null;

    /**
     * One Category has Many Categories.
     * @var ArrayCollection<int, Category>
     */
    #[OneToMany(targetEntity: Category::class, mappedBy: 'parentCategory')]
    private ArrayCollection $children;

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
    public function getParentCategory(): ?Category
    {
        return $this->parentCategory;
    }
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }
    public function setChildren(ArrayCollection $children): static{
        $this->children = $children;
        return $this;
    }
    public function setParentCategory(?Category $parentCategory): static
    {
        $this->parentCategory = $parentCategory;
        $this->parent_category_id = $parentCategory?->getCategoryId(); // Sync the ID with the object

        return $this;
    }
}
