<?php

namespace App\Entity;

use App\Repository\ScenarioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScenarioRepository::class)]
class Scenario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $NameScenario = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solution4 = null;

    #[ORM\ManyToOne(inversedBy: 'scenarios')]
    private ?Activity $Activity_id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameScenario(): ?string
    {
        return $this->NameScenario;
    }

    public function setNameScenario(?string $NameScenario): static
    {
        $this->NameScenario = $NameScenario;

        return $this;
    }

    public function getQuestion1(): ?string
    {
        return $this->question1;
    }

    public function setQuestion1(?string $question1): static
    {
        $this->question1 = $question1;

        return $this;
    }

    public function getSolution1(): ?string
    {
        return $this->solution1;
    }

    public function setSolution1(string $solution1): static
    {
        $this->solution1 = $solution1;

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

    public function getSolution4(): ?string
    {
        return $this->solution4;
    }

    public function setSolution4(?string $solution4): static
    {
        $this->solution4 = $solution4;

        return $this;
    }

    public function getActivityId(): ?Activity
    {
        return $this->Activity_id;
    }

    public function setActivityId(?Activity $Activity_id): static
    {
        $this->Activity_id = $Activity_id;

        return $this;
    }

}
