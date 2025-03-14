<?php

namespace App\Entity;

use App\Repository\ChefRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChefRepository::class)]
class Chef
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    private ?int $numero_telephone = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $photo = null;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite( $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail( $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumeroTelephone(): ?int
    {
        return $this->numero_telephone;
    }

    public function setNumeroTelephone( $numero_telephone): self
    {
        $this->numero_telephone = $numero_telephone;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto( $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
