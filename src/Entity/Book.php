<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cover;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="author")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favs")
     */
    private $fav_users;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->fav_users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavUsers(): Collection
    {
        return $this->fav_users;
    }

    public function addFavUser(User $favUser): self
    {
        if (!$this->fav_users->contains($favUser)) {
            $this->fav_users[] = $favUser;
            $favUser->addFav($this);
        }

        return $this;
    }

    public function removeFavUser(User $favUser): self
    {
        if ($this->fav_users->removeElement($favUser)) {
            $favUser->removeFav($this);
        }

        return $this;
    }
}
