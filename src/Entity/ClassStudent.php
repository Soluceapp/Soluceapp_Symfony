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
    private ?string $nameClass = null;
   
    #[ORM\OneToMany(mappedBy: 'classStudent', targetEntity: Activity::class)]
    private Collection $activityId;

    #[ORM\Column(nullable: true)]
    private ?float $moyenneActivity = null;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Dutil::class)]
    private Collection $dutils;

    #[ORM\Column(nullable: true)]
    private ?bool $accesFacture = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesChevaux = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesCompta = null;

    #[ORM\OneToMany(mappedBy: 'classe', targetEntity: Scenario::class, cascade: ['persist', 'remove'])]
    private Collection $scenarios;

    #[ORM\Column(nullable: true)]
    private ?bool $accesMotCroise = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesCours = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesFlashcardEco = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesFlashcardGestion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesEvalFlashcard = null;


    public function __construct()
    {
        $this->activityId = new ArrayCollection();
        $this->dutils = new ArrayCollection();
        $this->scenarios = new ArrayCollection();
        $this->flashCardEcos = new ArrayCollection();
        $this->flashCardGestions = new ArrayCollection();
  
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameClass(): ?string
    {
        return $this->nameClass;
    }

    public function setNameClass(string $nameClass): static
    {
        $this->nameClass = $nameClass;

        return $this;
    }


    /**
     * @return Collection<int, Activity>
     */
    public function getActivityId(): Collection
    {
        return $this->activityId;
    }

    public function addActivityId(Activity $activityId): static
    {
        if (!$this->activityId->contains($activityId)) {
            $this->activityId->add($activityId);
            $activityId->setClassStudent($this);
        }

        return $this;
    }

    public function removeActivityId(Activity $activityId): static
    {
        if ($this->activityId->removeElement($activityId)) {
            // set the owning side to null (unless already changed)
            if ($activityId->getClassStudent() === $this) {
                $activityId->setClassStudent(null);
            }
        }

        return $this;
    }

    public function getMoyenneActivity(): ?float
    {
        return $this->moyenneActivity;
    }

    public function setMoyenneActivity(?float $moyenneActivity): static
    {
        $this->moyenneActivity = $moyenneActivity;

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

    public function setAccesFacture(bool $accesFacture): static
    {
        $this->accesFacture = $accesFacture;

        return $this;
    }

    public function setAccesChevaux(bool $accesChevaux): static
    {
        $this->accesChevaux = $accesChevaux;

        return $this;
    }


    public function setAccesCompta(bool $accesCompta): static
    {
        $this->accesCompta = $accesCompta;

        return $this;
    }

    /**
     * @return Collection<int, Scenario>
     */
    public function getScenarios(): Collection
    {
        return $this->scenarios;
    }

    public function addScenario(Scenario $scenario): self
    {
        if (!$this->scenarios->contains($scenario)) {
            $this->scenarios->add($scenario);
            $scenario->setClasse($this);
        }

        return $this;
    }
    public function removeScenario(Scenario $scenario): self
    {
        if ($this->scenarios->removeElement($scenario)) {
            if ($scenario->getClasse() === $this) {
                $scenario->setClasse(null);
            }
        }

        return $this;
    }
    public function getAccesMotcroise(): ?bool
    {
        return $this->accesMotCroise;
    }


    public function getAccesChevaux(): ?bool
    {
        return $this->accesChevaux;
    }

    
    public function getAccesCompta(): ?bool
    {
        return $this->accesCompta;
    }

    public function getAccesFacture(): ?bool
    {
        return $this->accesFacture;
    }
    public function setAccesMotcroise(?bool $accesMotCroise): static
    {
        $this->accesMotCroise = $accesMotCroise;

        return $this;
    }

    public function isAccesCours(): ?bool
    {
        return $this->accesCours;
    }

    public function setAccesCours(?bool $accesCours): static
    {
        $this->accesCours = $accesCours;

        return $this;
    }
 
    public function getAccesFlashcardEco(): ?bool
    {
        return $this->accesFlashcardEco;
    }

    public function setAccesFlashCardEco(bool $accesFlashcardEco): static
    {
        $this->accesFlashcardEco = $accesFlashcardEco;

        return $this;
    }

    public function getAccesFlashCardGestion(): ?bool
    {
        return $this->accesFlashcardGestion;
    }

    public function setAccesFlashCardGestion(bool $accesFlashcardGestion): static
    {
        $this->accesFlashcardGestion = $accesFlashcardGestion;

        return $this;
    }

    public function getAccesEvalFlashcard(): ?bool
    {
        return $this->accesEvalFlashcard;
    }


    public function setAccesEvalFlashcard(bool $accesEvalFlashcard): static
    {
        $this->accesEvalFlashcard = $accesEvalFlashcard;

        return $this;
    }
  

}
