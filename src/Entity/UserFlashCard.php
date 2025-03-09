<?php

namespace App\Entity;

use App\Repository\UserFlashCardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFlashCardRepository::class)]
class UserFlashCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy:'userFlashCards')]
    private ?Dutil $dutil = null;

    #[ORM\ManyToOne(inversedBy: 'userFlashCard')]
    private ?FlashCardEco $flashCardEco = null;

    #[ORM\ManyToOne(inversedBy: 'userFlashCard')]
    private ?FlashCardGestion $flashCardGestion = null;

    #[ORM\ManyToOne(inversedBy: 'userFlashCard')]
    private ?FlashCardOutilGestion $flashCardOutilGestion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $natureFlashCard = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = 0;

    #[ORM\Column(nullable: true)]
    private ?int $sucessCount = 0;

    #[ORM\Column(nullable: true)]
    private ?int $failureCount = 0;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateReponse = null;


    // gets-----------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDutil(): ?Dutil
    {
        return $this->dutil;
    }

    public function getFlashCardEco(): ?FlashCardEco
    {
        return $this->flashCardEco;
    }

    public function getFlashCardGestion(): ?FlashCardGestion
    {
        return $this->flashCardGestion;
    }

    public function getFlashCardOutilGestion(): ?FlashCardOutilGestion
    {
        return $this->flashCardOutilGestion;
    }

    public function getFailureCount(): ?string
    {
        return $this->failureCount;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getSucessCount(): ?int
    {
        return $this->sucessCount;
    }

    public function getNatureFlashCard(): ?int
    {
        return $this->natureFlashCard;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    // sets-----------------------------------------

    public function setDutil(?Dutil $dutil): static
    {
        $this->dutil = $dutil;

        return $this;
    }

    public function setFlashCardEco(?FlashCardEco $flashCardEco): static
    {
        $this->flashCardEco = $flashCardEco;

        return $this;
    }

    public function setFlashCardGestion(?FlashCardGestion $flashCardGestion): static
    {
        $this->flashCardGestion = $flashCardGestion;

        return $this;
    }

    public function setFlashCardOutilGestion(?FlashCardOutilGestion $flashCardOutilGestion): static
    {
        $this->flashCardGestion = $flashCardOutilGestion;

        return $this;
    }


    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function setNatureFlashCard(?string $natureFlashCard): static
    {
        $this->natureFlashCard = $natureFlashCard;

        return $this;
    }


    public function setSucessCount(?int $sucessCount): static
    {
        $this->sucessCount = $sucessCount;

        return $this;
    }

    public function setFailureCount(?int $failureCount): static
    {
        $this->failureCount = $failureCount;

        return $this;
    }

    public function setDateReponse(?\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;
        return $this;
    }

}
