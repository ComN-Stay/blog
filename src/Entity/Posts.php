<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_add = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Teams $fk_team = null;

    #[ORM\ManyToOne(inversedBy: 'user_posts')]
    private ?Users $fk_user = null;

    /**
     * @var Collection<int, Tags>
     */
    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'posts')]
    private Collection $fk_tags;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->fk_tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDateAdd(): ?\DateTimeInterface
    {
        return $this->date_add;
    }

    public function setDateAdd(\DateTimeInterface $date_add): static
    {
        $this->date_add = $date_add;

        return $this;
    }

    public function getFkTeam(): ?Teams
    {
        return $this->fk_team;
    }

    public function setFkTeam(?Teams $fk_team): static
    {
        $this->fk_team = $fk_team;

        return $this;
    }

    public function getFkUser(): ?Users
    {
        return $this->fk_user;
    }

    public function setFkUser(?Users $fk_user): static
    {
        $this->fk_user = $fk_user;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getFkTags(): Collection
    {
        return $this->fk_tags;
    }

    public function addFkTag(Tags $fkTag): static
    {
        if (!$this->fk_tags->contains($fkTag)) {
            $this->fk_tags->add($fkTag);
        }

        return $this;
    }

    public function removeFkTag(Tags $fkTag): static
    {
        $this->fk_tags->removeElement($fkTag);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
