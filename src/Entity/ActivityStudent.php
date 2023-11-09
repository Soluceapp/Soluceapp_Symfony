<?php

namespace App\Entity;

use App\Repository\ActivityStudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityStudentRepository::class)]
class ActivityStudent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Solution1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Solution2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Solution3 = null;

    #[ORM\Column(length: 255)]
    private ?string $NameActivity = null;

    #[ORM\ManyToMany(targetEntity: ClassStudent::class, mappedBy: 'NameActivity')]
    private Collection $classStudents;

    public function __construct()
    {
        $this->classStudents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolution1(): ?string
    {
        return $this->Solution1;
    }

    public function setSolution1(?string $Solution1): static
    {
        $this->Solution1 = $Solution1;

        return $this;
    }

    public function getSolution2(): ?string
    {
        return $this->Solution2;
    }

    public function setSolution2(?string $Solution2): static
    {
        $this->Solution2 = $Solution2;

        return $this;
    }

    public function getSolution3(): ?string
    {
        return $this->Solution3;
    }

    public function setSolution3(?string $Solution3): static
    {
        $this->Solution3 = $Solution3;

        return $this;
    }

    public function getNameActivity(): ?string
    {
        return $this->NameActivity;
    }

    public function setNameActivity(string $NameActivity): static
    {
        $this->NameActivity = $NameActivity;

        return $this;
    }

    /**
     * @return Collection<int, ClassStudent>
     */
    public function getClassStudents(): Collection
    {
        return $this->classStudents;
    }

    public function addClassStudent(ClassStudent $classStudent): static
    {
        if (!$this->classStudents->contains($classStudent)) {
            $this->classStudents->add($classStudent);
            $classStudent->addNameActivity($this);
        }

        return $this;
    }

    public function removeClassStudent(ClassStudent $classStudent): static
    {
        if ($this->classStudents->removeElement($classStudent)) {
            $classStudent->removeNameActivity($this);
        }

        return $this;
    }
}
