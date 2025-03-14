<?php

namespace App\Entity;

use App\Entity\Post;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true,nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $pin = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $resetTokenExpiration = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vote::class)]
    private Collection $votes;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->votes = new ArrayCollection();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->tel;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->tel;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword( $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel( $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin( $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom( $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom( $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe( $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken( $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getResetTokenExpiration(): ?\DateTimeInterface
    {
        return $this->resetTokenExpiration;
    }

    public function setResetTokenExpiration(?\DateTimeInterface $resetTokenExpiration): self
    {
        $this->resetTokenExpiration = $resetTokenExpiration;

        return $this;
    }

    /**
     * @return Collection<int, Post\>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
            }
        }

        return $this;
    }

}
