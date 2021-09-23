<?php

namespace App\Entity;

class SortieSearch
{
    /**
     * @var Campus|null
     */
    private $campus;

    /**
     *@var boolean|null
     */
    private $organisateur;

    /**
     * @var string|null
     */
    private $nom;

    /**
     * @var \DateTime|null
     */
    private $date;

    /**
     * @var \DateTime|null
     */
    private $date2;

    /**
     * @var boolean|null
     */
    private $inscrit;

    /**
     * @var boolean|null
     */
    private $ended;

    /**
     * @var boolean|null
     */
    private $notInscrit;

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
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return SortieSearch
     */
    public function setDate(?\DateTime $date): SortieSearch
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate2(): ?\DateTime
    {
        return $this->date2;
    }

    /**
     * @param \DateTime|null $date2
     * @return SortieSearch
     */
    public function setDate2(?\DateTime $date2): SortieSearch
    {
        $this->date2 = $date2;
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





}