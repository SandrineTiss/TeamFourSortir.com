<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=5, max=50)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Sorties::class, mappedBy="campus", orphanRemoval=true)
     */
    private $sorties;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="campus", orphanRemoval=true)
     */
    private $rattaches;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->rattaches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Sorties[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sorties $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setCampus($this);
        }

        return $this;
    }

    public function removeSorty(Sorties $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getCampus() === $this) {
                $sorty->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRattaches(): Collection
    {
        return $this->rattaches;
    }

    public function addRattach(User $rattach): self
    {
        if (!$this->rattaches->contains($rattach)) {
            $this->rattaches[] = $rattach;
            $rattach->setCampus($this);
        }

        return $this;
    }

    public function removeRattach(User $rattach): self
    {
        if ($this->rattaches->removeElement($rattach)) {
            // set the owning side to null (unless already changed)
            if ($rattach->getCampus() === $this) {
                $rattach->setCampus(null);
            }
        }

        return $this;
    }
}
