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
   
    #[ORM\OneToMany(mappedBy: 'classStudent', targetEntity: Activity::class)]
    private Collection $activity_id;

    #[ORM\Column(nullable: true)]
    private ?float $moyenne_activity = null;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Dutil::class)]
    private Collection $dutils;

    #[ORM\Column]
    private ?bool $acces_facture = null;

    #[ORM\Column]
    private ?bool $acces_cheveaux = null;

    #[ORM\Column]
    private ?bool $acces_compta = null;

    public function __construct()
    {
        $this->activity_id = new ArrayCollection();
        $this->dutils = new ArrayCollection();
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
     * @return Collection<int, Activity>
     */
    public function getActivityId(): Collection
    {
        return $this->activity_id;
    }

    public function addActivityId(Activity $activityId): static
    {
        if (!$this->activity_id->contains($activityId)) {
            $this->activity_id->add($activityId);
            $activityId->setClassStudent($this);
        }

        return $this;
    }

    public function removeActivityId(Activity $activityId): static
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

    /**
     * @return Collection<int, Dutil>
     */
    public function getDutils(): Collection
    {
        return $this->dutils;
    }

    public function addDutil(Dutil $dutil): static
    {
        if (!$this->dutils->contains($dutil)) {
            $this->dutils->add($dutil);
            $dutil->setClasse($this);
        }

        return $this;
    }

    public function removeDutil(Dutil $dutil): static
    {
        if ($this->dutils->removeElement($dutil)) {
            // set the owning side to null (unless already changed)
            if ($dutil->getClasse() === $this) {
                $dutil->setClasse(null);
            }
        }

        return $this;
    }

    public function __toString():string
    {
        return $this->getNameClass();
    }

    public function isAccesFacture(): ?bool
    {
        return $this->acces_facture;
    }

    public function setAccesFacture(bool $acces_facture): static
    {
        $this->acces_facture = $acces_facture;

        return $this;
    }

    public function isAccesCheveaux(): ?bool
    {
        return $this->acces_cheveaux;
    }

    public function setAccesCheveaux(bool $acces_cheveaux): static
    {
        $this->acces_cheveaux = $acces_cheveaux;

        return $this;
    }

    public function isAccesCompta(): ?bool
    {
        return $this->acces_compta;
    }

    public function setAccesCompta(bool $acces_compta): static
    {
        $this->acces_compta = $acces_compta;

        return $this;
    }

}
