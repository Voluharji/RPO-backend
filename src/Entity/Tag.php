<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tag_id = null;

    #[ORM\Column(length: 255)]
    private ?string $tag_name = null;

    public function getId(): ?int
    {
        return $this->id;
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
