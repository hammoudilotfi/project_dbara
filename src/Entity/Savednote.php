<?php

namespace App\Entity;

use App\Repository\SavednoteRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SavednoteRepository::class)]
#[Groups("savednote:read")]
class Savednote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("savednote:read")]
    private ?int $id = null;


    #[ORM\Column(nullable: true)]
    #[Groups("savednote:read")]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'savednotes')]
    private ?User $abonnees = null;

    #[ORM\ManyToOne(inversedBy: 'savednote')]
    private ?Dbaretelchef $dbaretelchef = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote($note): self
    {
        $this->note = $note;

        return $this;
    }
    public function getDbaretelchef(): ?Dbaretelchef
    {
        return $this->dbaretelchef;
    }

    public function setDbaretelchef(?Dbaretelchef $dbaretelchef): void
    {
        $this->dbaretelchef = $dbaretelchef;
    }


    public function getAbonnees(): ?User
    {
        return $this->abonnees;
    }

    public function setAbonnees(?User $abonnees): void
    {
        $this->abonnees = $abonnees;
    }


}
