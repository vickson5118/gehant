<?php

declare(strict_types=1);

namespace src;

class Domaine{
    
    private ?int $id;
    private ?string $titre;
    private ?string $titreUrl;
    private ?string $description;
    private bool $bloquer;
    private ?string $dateCreation;
    private ?string $dateBlocage;
    private ?string $motifBlocage;
    private ?string $illustration;
    private ?int $nombreFormationTotal;
    private ?int $nombreFormationActive;
    private ?int $nombreFormationRedaction;
    private ?int $nombreFormationBloquer;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string|null
     */
    public function getTitreUrl(): ?string
    {
        return $this->titreUrl;
    }

    /**
     * @param string|null $titreUrl
     */
    public function setTitreUrl(?string $titreUrl): void
    {
        $this->titreUrl = $titreUrl;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isBloquer(): bool
    {
        return $this->bloquer;
    }

    /**
     * @param bool|null $bloquer
     */
    public function setBloquer(bool $bloquer): void
    {
        $this->bloquer = $bloquer;
    }

    /**
     * @return string|null
     */
    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    /**
     * @param string|null $dateCreation
     */
    public function setDateCreation(?string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return string|null
     */
    public function getDateBlocage(): ?string
    {
        return $this->dateBlocage;
    }

    /**
     * @param string|null $dateBlocage
     */
    public function setDateBlocage(?string $dateBlocage): void
    {
        $this->dateBlocage = $dateBlocage;
    }

    /**
     * @return string|null
     */
    public function getMotifBlocage(): ?string
    {
        return $this->motifBlocage;
    }

    /**
     * @param string|null $motifBlocage
     */
    public function setMotifBlocage(?string $motifBlocage): void
    {
        $this->motifBlocage = $motifBlocage;
    }

    /**
     * @return string|null
     */
    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    /**
     * @param string|null $illustration
     */
    public function setIllustration(?string $illustration): void
    {
        $this->illustration = $illustration;
    }

    /**
     * @return int|null
     */
    public function getNombreFormationTotal(): ?int
    {
        return $this->nombreFormationTotal;
    }

    /**
     * @param int|null $nombreFormationTotal
     */
    public function setNombreFormationTotal(?int $nombreFormationTotal): void
    {
        $this->nombreFormationTotal = $nombreFormationTotal;
    }

    /**
     * @return int|null
     */
    public function getNombreFormationActive(): ?int
    {
        return $this->nombreFormationActive;
    }

    /**
     * @param int|null $nombreFormationActive
     */
    public function setNombreFormationActive(?int $nombreFormationActive): void
    {
        $this->nombreFormationActive = $nombreFormationActive;
    }

    /**
     * @return int|null
     */
    public function getNombreFormationRedaction(): ?int
    {
        return $this->nombreFormationRedaction;
    }

    /**
     * @param int|null $nombreFormationRedaction
     */
    public function setNombreFormationRedaction(?int $nombreFormationRedaction): void
    {
        $this->nombreFormationRedaction = $nombreFormationRedaction;
    }

    /**
     * @return int|null
     */
    public function getNombreFormationBloquer(): ?int
    {
        return $this->nombreFormationBloquer;
    }

    /**
     * @param int|null $nombreFormationBloquer
     */
    public function setNombreFormationBloquer(?int $nombreFormationBloquer): void
    {
        $this->nombreFormationBloquer = $nombreFormationBloquer;
    }


    
}

