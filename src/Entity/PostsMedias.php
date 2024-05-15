<?php

namespace App\Entity;

use App\Repository\PostsMediasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsMediasRepository::class)]
class PostsMedias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\ManyToOne]
    private ?Posts $fk_post = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getFkPost(): ?Posts
    {
        return $this->fk_post;
    }

    public function setFkPost(?Posts $fk_post): static
    {
        $this->fk_post = $fk_post;

        return $this;
    }
}
