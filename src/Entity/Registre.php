<?php

namespace App\Entity;

use App\Repository\RegistreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistreRepository::class)]
class Registre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $dutil = null;

    #[ORM\Column(length: 255)]
    private ?string $dmp = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $points = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\Column(length: 10)]
    private ?string $classe = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $cat = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $sol1 = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $sol2 = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $dcle = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $dpts1 = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $dpts2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDutil(): ?string
    {
        return $this->dutil;
    }

    public function setDutil(string $dutil): static
    {
        $this->dutil = $dutil;

        return $this;
    }

    public function getDmp(): ?string
    {
        return $this->dmp;
    }

    public function setDmp(string $dmp): static
    {
        $this->dmp = $dmp;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getCat(): ?int
    {
        return $this->cat;
    }

    public function setCat(?int $cat): static
    {
        $this->cat = $cat;

        return $this;
    }

    public function getSol1(): ?string
    {
        return $this->sol1;
    }

    public function setSol1(?string $sol1): static
    {
        $this->sol1 = $sol1;

        return $this;
    }

    public function getSol2(): ?string
    {
        return $this->sol2;
    }

    public function setSol2(?string $sol2): static
    {
        $this->sol2 = $sol2;

        return $this;
    }

    public function getDcle(): ?string
    {
        return $this->dcle;
    }

    public function setDcle(?string $dcle): static
    {
        $this->dcle = $dcle;

        return $this;
    }

    public function getDpts1(): ?int
    {
        return $this->dpts1;
    }

    public function setDpts1(?int $dpts1): static
    {
        $this->dpts1 = $dpts1;

        return $this;
    }

    public function getDpts2(): ?int
    {
        return $this->dpts2;
    }

    public function setDpts2(?int $dpts2): static
    {
        $this->dpts2 = $dpts2;

        return $this;
    }
}
