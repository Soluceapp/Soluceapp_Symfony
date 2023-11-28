<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution3 = null;

    #[ORM\ManyToOne(inversedBy: 'activity_id')]
    private ?ClassStudent $classStudent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolution1(): ?string
    {
        return $this->solution1;
    }

    public function setSolution1(?string $solution1): static
    {
        $this->solution1 = $solution1;

        return $this;
    }

    public function getSolution2(): ?string
    {
        return $this->solution2;
    }

    public function setSolution2(?string $solution2): static
    {
        $this->solution2 = $solution2;

        return $this;
    }

    public function getSolution3(): ?string
    {
        return $this->solution3;
    }

    public function setSolution3(?string $solution3): static
    {
        $this->solution3 = $solution3;

        return $this;
    }

    public function getClassStudent(): ?ClassStudent
    {
        return $this->classStudent;
    }

    public function setClassStudent(?ClassStudent $classStudent): static
    {
        $this->classStudent = $classStudent;

        return $this;
    }
}
