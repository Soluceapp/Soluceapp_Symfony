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
    private ?string $Namedomaine = null;

    #[ORM\OneToMany(mappedBy: 'id_domain', targetEntity: Dutil::class)]
    private Collection $dutils;

    public function __construct()
    {
        $this->dutils = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamedomaine(): ?string
    {
        return $this->Namedomaine;
    }

    public function setNamedomaine(?string $Namedomaine): static
    {
        $this->Namedomaine = $Namedomaine;

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
            $dutil->setIdDomain($this);
        }

        return $this;
    }

    public function removeDutil(Dutil $dutil): static
    {
        if ($this->dutils->removeElement($dutil)) {
            // set the owning side to null (unless already changed)
            if ($dutil->getIdDomain() === $this) {
                $dutil->setIdDomain(null);
            }
        }

        return $this;
    }

   
}
