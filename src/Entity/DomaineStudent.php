<?php

namespace App\Entity;

use App\Repository\DomaineStudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: DomaineStudentRepository::class)]
class DomaineStudent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nameDomaine = null;

    #[ORM\OneToMany(mappedBy: 'id_domain', targetEntity: Dutil::class)]
    private Collection $dutils;

    #[ORM\Column(nullable: true)]
    private ?bool $accesEvalFlashcardSeconde = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesEvalFlashcardPremiere = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesEvalFlashcardTerminale = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesEvalFlashcardFac = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesEvalFlashcardSpecial = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesFlashcardOutilGestion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $accesFlashcardEcoDroit = null;

    // ----------------------------------------------------------

    public function __construct()
    {
        $this->dutils = new ArrayCollection();
    }

   // get -------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameDomaine(): ?string
    {
        return $this->nameDomaine;
    }

    /**
     * @return Collection<int, Dutil>
     */
    public function getDutils(): Collection
    {
        return $this->dutils;
    }

    public function getAccesEvalFlashcardSeconde(): ?bool
    {
        return $this->accesEvalFlashcardSeconde;
    }


    public function getAccesEvalFlashcardPremiere(): ?bool
    {
        return $this->accesEvalFlashcardPremiere;
    }

    public function getAccesEvalFlashcardTerminale(): ?bool
    {
        return $this->accesEvalFlashcardTerminale;
    }

    public function getAccesEvalFlashcardFac(): ?bool
    {
        return $this->accesEvalFlashcardFac;
    }

    public function getAccesEvalFlashcardSpecial(): ?bool
    {
        return $this->accesEvalFlashcardSpecial;
    }

    public function getAccesFlashcardOutilGestion(): ?bool
    {
        return $this->accesFlashcardOutilGestion;
    }

    public function getAccesFlashcardEcoDroit(): ?bool
    {
        return $this->accesFlashcardEcoDroit;
    }

    // set ------------------------------------------------------

    public function setNamedomaine(?string $nameDomaine): static
    {
        $this->nameDomaine = $nameDomaine;

        return $this;
    }

    
    public function addDutil(Dutil $dutil): static
    {
        if (!$this->dutils->contains($dutil)) {
            $this->dutils->add($dutil);
            $dutil->setIdDomain($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->nameDomaine;
    }
   
    public function setAccesEvalFlashcardSeconde(bool $accesEvalFlashcardSeconde): static
    {
        $this->accesEvalFlashcardSeconde = $accesEvalFlashcardSeconde;

        return $this;
    }

    public function setAccesEvalFlashcardPremiere(bool $accesEvalFlashcardPremiere): static
    {
        $this->accesEvalFlashcardPremiere = $accesEvalFlashcardPremiere;

        return $this;
    }

    public function setAccesEvalFlashcardTerminale(bool $accesEvalFlashcardTerminale): static
    {
        $this->accesEvalFlashcardTerminale = $accesEvalFlashcardTerminale;

        return $this;
    }

    public function setAccesEvalFlashcardFac(bool $accesEvalFlashcardFac): static
    {
        $this->accesEvalFlashcardFac = $accesEvalFlashcardFac;

        return $this;
    }

    public function setAccesEvalFlashcardSpecial(bool $accesEvalFlashcardSpecial): static
    {
        $this->accesEvalFlashcardSpecial = $accesEvalFlashcardSpecial;

        return $this;
    }

    public function setAccesFlashCardOutilGestion(bool $accesFlashcardOutilGestion): static
    {
        $this->accesFlashcardOutilGestion = $accesFlashcardOutilGestion;

        return $this;
    }

    public function setAccesFlashCardEcoDroit(bool $accesFlashcardEcoDroit): static
    {
        $this->accesFlashcardEcoDroit = $accesFlashcardEcoDroit;

        return $this;
    }


}
