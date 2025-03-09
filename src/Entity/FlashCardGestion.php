<?php

namespace App\Entity;

use App\Repository\FlashCardGestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashCardGestionRepository::class)]
class FlashCardGestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $rectoGestion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $versoGestion = null;

    #[ORM\OneToMany(mappedBy: 'flashCardGestion', targetEntity: UserFlashCard::class)]
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

    public function getRectoGestion(): ?string
    {
        return $this->rectoGestion;
    }

    public function setRectoGestion(?string $rectoGestion): static
    {
        $this->rectoGestion = $rectoGestion;

        return $this;
    }

    public function getVersoGestion(): ?string
    {
        return $this->versoGestion;
    }

    public function setVersoGestion(?string $versoGestion): static
    {
        $this->versoGestion = $versoGestion;

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
            $userFlashCard->setFlashCardGestion($this);
        }

        return $this;
    }

    public function removeUserFlashCard(UserFlashCard $userFlashCard): static
    {
        if ($this->userFlashCard->removeElement($userFlashCard)) {
            // set the owning side to null (unless already changed)
            if ($userFlashCard->getFlashCardGestion() === $this) {
                $userFlashCard->setFlashCardGestion(null);
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
