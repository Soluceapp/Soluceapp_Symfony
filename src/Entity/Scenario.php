<?php

namespace App\Entity;

use App\Repository\ScenarioRepository;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\ManyToOne(inversedBy: 'scenarios')]
    private ?Activity $Activity_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question5 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $question6 = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $solution1 = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $solution2 = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $solution3 = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $solution4 = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $solution5 = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $solution6 = null;

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


    public function getActivityId(): ?Activity
    {
        return $this->Activity_id;
    }

    public function setActivityId(?Activity $Activity_id): static
    {
        $this->Activity_id = $Activity_id;

        return $this;
    }

    public function getQuestion2(): ?string
    {
        return $this->question2;
    }

    public function setQuestion2(?string $question2): static
    {
        $this->question2 = $question2;

        return $this;
    }

    public function getQuestion3(): ?string
    {
        return $this->question3;
    }

    public function setQuestion3(?string $question3): static
    {
        $this->question3 = $question3;

        return $this;
    }

    public function getQuestion4(): ?string
    {
        return $this->question4;
    }

    public function setQuestion4(?string $question4): static
    {
        $this->question4 = $question4;

        return $this;
    }

    public function getQuestion5(): ?string
    {
        return $this->question5;
    }

    public function setQuestion5(string $question5): static
    {
        $this->question5 = $question5;

        return $this;
    }

    public function getQuestion6(): ?string
    {
        return $this->question6;
    }

    public function setQuestion6(?string $question6): static
    {
        $this->question6 = $question6;

        return $this;
    }

    public function getSolution1(): ?array
    {
        return $this->solution1;
    }

    public function setSolution1(?array $solution1): static
    {
        $this->solution1 = $solution1;

        return $this;
    }

    public function getSolution2(): ?array
    {
        return $this->solution2;
    }

    public function setSolution2(?array $solution2): static
    {
        $this->solution2 = $solution2;

        return $this;
    }

    public function getSolution3(): ?array
    {
        return $this->solution3;
    }

    public function setSolution3(?array $solution3): static
    {
        $this->solution3 = $solution3;

        return $this;
    }

    public function getSolution4(): ?array
    {
        return $this->solution4;
    }

    public function setSolution4(?array $solution4): static
    {
        $this->solution4 = $solution4;

        return $this;
    }

    public function getSolution5(): ?array
    {
        return $this->solution5;
    }

    public function setSolution5(?array $solution5): static
    {
        $this->solution5 = $solution5;

        return $this;
    }

    public function getSolution6(): ?array
    {
        return $this->solution6;
    }

    public function setSolution6(?array $solution6): static
    {
        $this->solution6 = $solution6;

        return $this;
    }



}
