<?php

namespace App\Entity;

use App\Repository\DutilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: DutilRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il y a déjà un compte avec ce mail. Changez de mot de passe pour recevoir un nouveau mail.')]
class Dutil implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'string', length:100, nullable: true)]
    private $resetToken = null;

    #[ORM\Column(type:'datetime_immutable',options:['default'=>'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'dutils')]
    private ?ClassStudent $classe = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $scenarioFait = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $limParticipation = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $motCroiseFait = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $coursFait = null;

    #[ORM\ManyToOne(targetEntity: DomaineStudent::class,inversedBy: 'dutils')]
    private ?DomaineStudent $id_domain = null; // nommage spécifique

    #[ORM\Column(nullable: true)]
    private ?float $Note = null;

    #[ORM\Column(nullable: true)]
    private ?float $noteEvalEco = null;

    #[ORM\Column(nullable: true)]
    private ?float $noteEvalGestion = null;

    #[ORM\Column(nullable: true)]
    private ?float $noteEvalOutilGestion = null;

    #[ORM\OneToMany(mappedBy: 'dutil', targetEntity: UserFlashCard::class)]
    private Collection $userFlashCards;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getScenarioFait(): ?array
    {
        return $this->scenarioFait;
    }

    public function setScenarioFait(?array $scenarioFait): static
    {
        $this->scenarioFait = $scenarioFait;

        return $this;
    }

    public function getLimParticipation(): ?array
    {
        return $this->limParticipation;
    }

    public function setLimparticipation(?array $limParticipation): static
    {
        $this->limParticipation = $limParticipation;

        return $this;
    }

    public function getMotCroiseFait(): ?array
    {
        return $this->motCroiseFait;
    }

    public function setMotCroiseFait(?array $motCroiseFait): static
    {
        $this->motCroiseFait = $motCroiseFait;

        return $this;
    }

    public function getCoursFait(): ?array
    {
        return $this->coursFait;
    }

    public function setCoursFait(?array $coursFait): static
    {
        $this->coursFait = $coursFait;

        return $this;
    }

    public function getIdDomain(): ?DomaineStudent
    {
        return $this->id_domain;
    }

    public function setIdDomain(DomaineStudent $idDomain): static
    {
        $this->id_domain =  $idDomain;

        return $this;
    }

    // Permet d'éviter une erreur sur recupnote.
   public function __toString():string
   {

   return $this->getNom();
   }

   public function getNote(): ?float
   {
       return $this->Note;
   }

   public function setNote(?float $Note): static
   {
       $this->Note = $Note;

       return $this;
   }


   public function getNoteEvalEco(): ?float
   {
       return $this->noteEvalEco;
   }

   public function setNoteEvalEco(?float $noteEvalEco): static
   {
       $this->noteEvalEco = $noteEvalEco;

       return $this;
   }
   public function getNoteEvalGestion(): ?float
   {
       return $this->noteEvalGestion;
   }

   public function setNoteEvalGestion(?float $noteEvalGestion): static
   {
       $this->noteEvalGestion = $noteEvalGestion;

       return $this;
   }

   public function getNoteEvalOutilGestion(): ?float
   {
       return $this->noteEvalOutilGestion;
   }

   public function setNoteEvalOutilGestion(?float $noteEvalOutilGestion): static
   {
       $this->noteEvalOutilGestion = $noteEvalOutilGestion;

       return $this;
   }

}
