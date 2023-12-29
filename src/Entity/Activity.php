<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activity_id')]
    private ?ClassStudent $classStudent = null;

    #[ORM\Column(length: 100)]
    private ?string $name_activity = null;

    #[ORM\Column]
    private ?int $coefficient = null;

    #[ORM\OneToMany(mappedBy: 'Activity_id', targetEntity: Scenario::class)]
    private Collection $scenarios;

    public function __construct()
    {
        $this->scenarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNameActivity(): ?string
    {
        return $this->name_activity;
    }

    public function setNameActivity(string $name_activity): static
    {
        $this->name_activity = $name_activity;

        return $this;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(int $coefficient): static
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * @return Collection<int, Scenario>
     */
    public function getScenarios(): Collection
    {
        return $this->scenarios;
    }

    public function addScenario(Scenario $scenario): static
    {
        if (!$this->scenarios->contains($scenario)) {
            $this->scenarios->add($scenario);
            $scenario->setActivityId($this);
        }

        return $this;
    }

    public function removeScenario(Scenario $scenario): static
    {
        if ($this->scenarios->removeElement($scenario)) {
            // set the owning side to null (unless already changed)
            if ($scenario->getActivityId() === $this) {
                $scenario->setActivityId(null);
            }
        }

        return $this;
    }
}
