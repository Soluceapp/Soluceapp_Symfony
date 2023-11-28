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

   
    #[ORM\OneToMany(mappedBy: 'NameClass', targetEntity: Student::class)]
    private Collection $students;

    #[ORM\OneToMany(mappedBy: 'classStudent', targetEntity: activity::class)]
    private Collection $activity_id;

    #[ORM\Column(nullable: true)]
    private ?float $moyenne_activity = null;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->activity_id = new ArrayCollection();
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

    /**
     * @return Collection<int, activity>
     */
    public function getActivityId(): Collection
    {
        return $this->activity_id;
    }

    public function addActivityId(activity $activityId): static
    {
        if (!$this->activity_id->contains($activityId)) {
            $this->activity_id->add($activityId);
            $activityId->setClassStudent($this);
        }

        return $this;
    }

    public function removeActivityId(activity $activityId): static
    {
        if ($this->activity_id->removeElement($activityId)) {
            // set the owning side to null (unless already changed)
            if ($activityId->getClassStudent() === $this) {
                $activityId->setClassStudent(null);
            }
        }

        return $this;
    }

    public function getMoyenneActivity(): ?float
    {
        return $this->moyenne_activity;
    }

    public function setMoyenneActivity(?float $moyenne_activity): static
    {
        $this->moyenne_activity = $moyenne_activity;

        return $this;
    }
}
