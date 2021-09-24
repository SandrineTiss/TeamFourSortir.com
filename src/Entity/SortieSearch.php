<?php

namespace App\Entity;

use App\Entity\Campus;

class SortieSearch
{

    private ?Campus $campus = null;
    private ?string $nom = null;
    private ?\DateTime $dateMin = null;
    private ?\DateTime $datemax = null;

    private ?bool $organisateur = false;
    private ?bool $inscrit = false;
    private ?bool $ended = false;
    private ?bool $notInscrit = false;

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     * @return SortieSearch
     */
    public function setCampus(?Campus $campus): SortieSearch
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     * @return SortieSearch
     */
    public function setNom(?string $nom): SortieSearch
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateMin(): ?\DateTime
    {
        return $this->dateMin;
    }

    /**
     * @param \DateTime|null $dateMin
     * @return SortieSearch
     */
    public function setDateMin(?\DateTime $dateMin): SortieSearch
    {
        $this->dateMin = $dateMin;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDatemax(): ?\DateTime
    {
        return $this->datemax;
    }

    /**
     * @param \DateTime|null $datemax
     * @return SortieSearch
     */
    public function setDatemax(?\DateTime $datemax): SortieSearch
    {
        $this->datemax = $datemax;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    /**
     * @param bool|null $organisateur
     * @return SortieSearch
     */
    public function setOrganisateur(?bool $organisateur): SortieSearch
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool|null $inscrit
     * @return SortieSearch
     */
    public function setInscrit(?bool $inscrit): SortieSearch
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEnded(): ?bool
    {
        return $this->ended;
    }

    /**
     * @param bool|null $ended
     * @return SortieSearch
     */
    public function setEnded(?bool $ended): SortieSearch
    {
        $this->ended = $ended;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getNotInscrit(): ?bool
    {
        return $this->notInscrit;
    }

    /**
     * @param bool|null $notInscrit
     * @return SortieSearch
     */
    public function setNotInscrit(?bool $notInscrit): SortieSearch
    {
        $this->notInscrit = $notInscrit;
        return $this;
    }


    public function getFiltres()
    {
        return $this->filtres;
    }

    public function setFiltres($tableau): void
    {
        $this->filtres[] = array();
        $this->filtres[] = $tableau;
    }

    public function setFiltre(string $clef, bool $valeur): void
    {
        $this->filtres[$clef] = $valeur;
    }

}