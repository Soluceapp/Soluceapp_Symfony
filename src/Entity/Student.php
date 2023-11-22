<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $Points = null;

    #[ORM\Column(nullable: true)]
    private ?int $Notes = null;

    #[ORM\OneToOne(targetEntity:"App\Entity\Dutil",inversedBy:"student")]
    #[ORM\JoinColumn(name:"dutil_id",referencedColumnName:"id")]
    private ?Dutil $dutil = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?ClassStudent $NameClass = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoints(): ?int
    {
        return $this->Points;
    }

    public function setPoints(?int $Points): static
    {
        $this->Points = $Points;

        return $this;
    }

    public function getNotes(): ?int
    {
        return $this->Notes;
    }

    public function setNotes(?int $Notes): static
    {
        $this->Notes = $Notes;

        return $this;
    }

    public function getNameClass(): ?ClassStudent
    {
        return $this->NameClass;
    }

    public function setNameClass(?ClassStudent $NameClass): static
    {
        $this->NameClass = $NameClass;

        return $this;
    }

    public function setId(?Dutil $id): static
    {
        $this->id = $id;

        return $this;
    }
}
