<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 180, unique: true)]
  #[Assert\NotBlank(message: 'Le champ {{ label }} ne peut pas être vide')]
  private ?string $email = null;

  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string The hashed password
   */
  #[ORM\Column]
  private ?string $password = null;

  #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
  private Collection $commandes;

  #[ORM\OneToMany(mappedBy: 'user', targetEntity: Avis::class)]
  private Collection $avis;

  #[ORM\Column(length: 255, nullable: true)]
  #[Assert\NotBlank]
  private ?string $pseudo = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Assert\NotBlank(message: 'Vous ne pouvez pas être non-binaire')]
  private ?string $sexe = null;

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Assert\NotBlank]
  private ?string $nom = null;

  #[ORM\Column(length: 255, nullable: true)]
  #[Assert\NotBlank]
  private ?string $prenom = null;

  public function __construct()
  {
    $this->commandes = new ArrayCollection();
    $this->avis = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
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

  public function setRoles(array $roles): self
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

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  /**
   * @return Collection<int, Commande>
   */
  public function getCommandes(): Collection
  {
    return $this->commandes;
  }

  public function addCommande(Commande $commande): self
  {
    if (!$this->commandes->contains($commande)) {
      $this->commandes->add($commande);
      $commande->setUser($this);
    }

    return $this;
  }

  public function removeCommande(Commande $commande): self
  {
    if ($this->commandes->removeElement($commande)) {
      // set the owning side to null (unless already changed)
      if ($commande->getUser() === $this) {
        $commande->setUser(null);
      }
    }

    return $this;
  }

  /**
   * @return Collection<int, Avis>
   */
  public function getAvis(): Collection
  {
    return $this->avis;
  }

  public function addAvis(Avis $avis): self
  {
    if (!$this->avis->contains($avis)) {
      $this->avis->add($avis);
      $avis->setUser($this);
    }

    return $this;
  }

  public function removeAvis(Avis $avis): self
  {
    if ($this->avis->removeElement($avis)) {
      // set the owning side to null (unless already changed)
      if ($avis->getUser() === $this) {
        $avis->setUser(null);
      }
    }

    return $this;
  }

  public function getPseudo(): ?string
  {
    return $this->pseudo;
  }

  public function setPseudo(?string $pseudo): self
  {
    $this->pseudo = $pseudo;

    return $this;
  }

  public function getSexe(): ?string
  {
    return $this->sexe;
  }

  public function setSexe(?string $sexe): self
  {
    $this->sexe = $sexe;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(?\DateTimeImmutable $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getNom(): ?string
  {
    return $this->nom;
  }

  public function setNom(?string $nom): self
  {
    $this->nom = $nom;

    return $this;
  }

  public function getPrenom(): ?string
  {
    return $this->prenom;
  }

  public function setPrenom(?string $prenom): self
  {
    $this->prenom = $prenom;

    return $this;
  }
}
