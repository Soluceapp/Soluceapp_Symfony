<?php

namespace App\Entity;

use App\Repository\FlashCardEcoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FlashCardEcoRepository::class)]
class FlashCardEco
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $rectoEco = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $versoEco = null;

    #[ORM\OneToMany(mappedBy: 'flashCardEco', targetEntity: UserFlashCard::class)]
    private Collection $userFlashCard;

    #[ORM\ManyToOne(targetEntity: ClassStudent::class)]
    private ?ClassStudent $classe = null;

    public function __construct()
    {
        $this->IdClasse = new ArrayCollection();
        $this->UserFlashCard = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRectoEco(): ?string
    {
        return $this->rectoEco;
    }

    public function setRectoEco(?string $rectoEco): static
    {
        $this->rectoEco = $rectoEco;

        return $this;
    }

    public function getVersoEco(): ?string
    {
        return $this->versoEco;
    }

    public function setVersoEco(?string $versoEco): static
    {
        $this->versoEco = $versoEco;

        return $this;
    }

    /**
     * @return Collection<int, UserFlashCard>
     */
    public function getUserFlashCard(): Collection
    {
        return $this->userFlashCard;
    }

    public function addUserFlashCard(UserFlashCard $userFlashCard): static
    {
        if (!$this->userFlashCard->contains($userFlashCard)) {
            $this->userFlashCard->add($userFlashCard);
            $userFlashCard->setFlashCardEco($this);
        }

        return $this;
    }

    public function removeUserFlashCard(UserFlashCard $userFlashCard): static
    {
        if ($this->userFlashCard->removeElement($userFlashCard)) {
            // set the owning side to null (unless already changed)
            if ($userFlashCard->getFlashCardEco() === $this) {
                $userFlashCard->setFlashCardEco(null);
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
