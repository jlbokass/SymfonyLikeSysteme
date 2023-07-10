<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: LikedPost::class)]
    private Collection $likedPosts;

    public function __construct()
    {
        $this->likedPosts = new ArrayCollection();
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

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): static
    {
        $this->introduction = $introduction;

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

    /**
     * @return Collection<int, LikedPost>
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(LikedPost $likedPost): static
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts->add($likedPost);
            $likedPost->setPost($this);
        }

        return $this;
    }

    public function removeLikedPost(LikedPost $likedPost): static
    {
        if ($this->likedPosts->removeElement($likedPost)) {
            // set the owning side to null (unless already changed)
            if ($likedPost->getPost() === $this) {
                $likedPost->setPost(null);
            }
        }

        return $this;
    }

    /**
     * To know if a user like a post
     *
     * @param User $user
     *
     * @return bool
     */
    public function isLikedByUser(User $user): bool
    {
        foreach ($this->likedPosts as $likedPost) {
            if ($likedPost->getUser() === $user) return true;
        }
        return false;
    }
}
