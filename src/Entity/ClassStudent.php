<?php

namespace App\Entity;

use App\Repository\ClassStudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassStudentRepository::class)]
class ClassStudent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NameClass = null;

    #[ORM\ManyToMany(targetEntity: ActivityStudent::class, inversedBy: 'classStudents')]
    private Collection $NameActivity;

    #[ORM\OneToMany(mappedBy: 'NameClass', targetEntity: Student::class)]
    private Collection $students;

    public function __construct()
    {
        $this->NameActivity = new ArrayCollection();
        $this->students = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameClass(): ?string
    {
        return $this->NameClass;
    }

    public function setNameClass(string $NameClass): static
    {
        $this->NameClass = $NameClass;

        return $this;
    }

    /**
     * @return Collection<int, ActivityStudent>
     */
    public function getNameActivity(): Collection
    {
        return $this->NameActivity;
    }

    public function addNameActivity(ActivityStudent $nameActivity): static
    {
        if (!$this->NameActivity->contains($nameActivity)) {
            $this->NameActivity->add($nameActivity);
        }

        return $this;
    }

    public function removeNameActivity(ActivityStudent $nameActivity): static
    {
        $this->NameActivity->removeElement($nameActivity);

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setNameClass($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getNameClass() === $this) {
                $student->setNameClass(null);
            }
        }

        return $this;
    }
}
