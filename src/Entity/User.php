<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="author")
     */
    private $books;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, inversedBy="fav_users")
     */
    private $favs;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->favs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->pseudo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBooks(Book $books): self
    {
        if (!$this->books->contains($books)) {
            $this->books[] = $books;
            $books->setBooks($this);
        }

        return $this;
    }

    public function removeBooks(Book $books): self
    {
        if ($this->books->removeElement($books)) {
            // set the owning side to null (unless already changed)
            if ($books->getBooks() === $this) {
                $books->setBooks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getFav(): Collection
    {
        return $this->favs;
    }

    public function addFav(Book $fav): self
    {
        if (!$this->favs->contains($fav)) {
            $this->favs[] = $fav;
        }

        return $this;
    }

    public function removeFav(Book $fav): self
    {
        $this->favs->removeElement($fav);

        return $this;
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function isInFav(Book $book): bool
    {
        if ($this->favs->contains($book)) {
            return true;
        } else {
            return false;
        }
    }
}
