<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(min=5, max=180)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, max=255)
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, max=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(min=10, max=10)
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="organisateur", orphanRemoval=true, cascade={"persist"})
     */
    private $estOrganisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Sorties::class, mappedBy="inscrits")
     */
    private $estInscrit;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="rattaches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @Assert\Length(max=30, min=3)
     * @Assert\NotBlank(message="Vous devez renseigner un pseudo")
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $pseudo;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="utilisateur")
     */
    private $image;


    public function __construct($image)
    {
        $this->estOrganisateur = new ArrayCollection();
        $this->estInscrit = new ArrayCollection();
        $image->setName('imageParDefaut.png');
        $this->setImage($image);
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
    public function getUsername(): string
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
     * @see UserInterface
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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getEstOrganisateur(): Collection
    {
        return $this->estOrganisateur;
    }

    public function addEstOrganisateur(Sorties $estOrganisateur): self
    {
        if (!$this->estOrganisateur->contains($estOrganisateur)) {
            $this->estOrganisateur[] = $estOrganisateur;
            $estOrganisateur->setOrganisateur($this);
        }

        return $this;
    }

    public function removeEstOrganisateur(Sorties $estOrganisateur): self
    {
        if ($this->estOrganisateur->removeElement($estOrganisateur)) {
            // set the owning side to null (unless already changed)
            if ($estOrganisateur->getOrganisateur() === $this) {
                $estOrganisateur->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sorties[]
     */
    public function getEstInscrit(): Collection
    {
        return $this->estInscrit;
    }

    public function addEstInscrit(Sorties $estInscrit): self
    {
        if (!$this->estInscrit->contains($estInscrit)) {
            $this->estInscrit[] = $estInscrit;
            $estInscrit->addInscrit($this);
        }

        return $this;
    }

    public function removeEstInscrit(Sorties $estInscrit): self
    {
        if ($this->estInscrit->removeElement($estInscrit)) {
            $estInscrit->removeInscrit($this);
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }
}
