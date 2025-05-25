<?php

namespace App\Entity;

use App\Repository\FlashCardOutilGestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashCardOutilGestionRepository::class)]
class FlashCardOutilGestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rectoOutilGestion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $versoOutilGestion = null;

    #[ORM\OneToMany(mappedBy: 'flashCardOutilGestion', targetEntity: UserFlashCard::class)]
    private Collection $userFlashCard;


    #[ORM\ManyToOne(targetEntity: ClassStudent::class)]
    private ?ClassStudent $classe = null;


    public function __construct()
    {
        $this->userFlashCard = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRectoOutilGestion(): ?string
    {
        return $this->rectoOutilGestion;
    }

    public function setRectoOutilGestion(?string $rectoOutilGestion): static
    {
        $this->rectoOutilGestion = $rectoOutilGestion;

        return $this;
    }

    public function getVersoOutilGestion(): ?string
    {
        return $this->versoOutilGestion;
    }

    public function setVersoOutilGestion(?string $versoOutilGestion): static
    {
        $this->versoOutilGestion = $versoOutilGestion;

        return $this;
    }

    /**
     * @return Collection<int, UserFlashCard>
     */
    public function getUserFlashCards(): Collection
    {
        return $this->userFlashCard;
    }

    
    public function addUserFlashCard(UserFlashCard $userFlashCard): static
    {
        if (!$this->userFlashCard->contains($userFlashCard)) {
            $this->userFlashCard->add($userFlashCard);
            $userFlashCard->setFlashCardOutilGestion($this);
        }

        return $this;
    }

    public function removeUserFlashCard(UserFlashCard $userFlashCard): static
    {
        if ($this->userFlashCard->removeElement($userFlashCard)) {
            // set the owning side to null (unless already changed)
            if ($userFlashCard->getFlashCardOutilGestion() === $this) {
                $userFlashCard->setFlashCardOutilGestion(null);
            }
        }

        return $this;
    }


    public function getClasse(): ?ClassStudent
    {
        return $this->classe;
    }

    public function setClasse(?ClassStudent $classe): static
    {
        $this->classe = $classe;

        return $this;
    }


}
