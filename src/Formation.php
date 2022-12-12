<?php

declare(strict_types=1);

namespace src;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");



class Formation{
    
    private ?int $id;
    private ?string $titre;
    private ?string $titreUrl;
    private Domaine $domaine;
    private Utilisateur $auteur;
    private ?string $but;
    private ?string $objectifs;
    private ?string $prerequis;
    private ?string $cibles;
    private ?string $description;
    private ?string $motifBlocage;
    private ?string $dateCreation;
    private ?string $dateBlocage;
    private ?string $dateDebut;
    private ?string $dateFin;
    private ?string $lieu;
    private bool $bloquer;
    private ?int $prix;
    private ?string $illustration;
    private bool $redactionFinished;
    private ?int $nombreAchat;

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
     * @return Domaine
     */
    public function getDomaine(): Domaine
    {
        return $this->domaine;
    }

    /**
     * @param Domaine $domaine
     */
    public function setDomaine(Domaine $domaine): void
    {
        $this->domaine = $domaine;
    }

    /**
     * @return Utilisateur
     */
    public function getAuteur(): Utilisateur
    {
        return $this->auteur;
    }

    /**
     * @param Utilisateur $auteur
     */
    public function setAuteur(Utilisateur $auteur): void
    {
        $this->auteur = $auteur;
    }

    /**
     * @return string|null
     */
    public function getBut(): ?string
    {
        return $this->but;
    }

    /**
     * @param string|null $but
     */
    public function setBut(?string $but): void
    {
        $this->but = $but;
    }

    /**
     * @return string|null
     */
    public function getObjectifs(): ?string
    {
        return $this->objectifs;
    }

    /**
     * @param string|null $objectifs
     */
    public function setObjectifs(?string $objectifs): void
    {
        $this->objectifs = $objectifs;
    }

    /**
     * @return string|null
     */
    public function getPrerequis(): ?string
    {
        return $this->prerequis;
    }

    /**
     * @param string|null $prerequis
     */
    public function setPrerequis(?string $prerequis): void
    {
        $this->prerequis = $prerequis;
    }

    /**
     * @return string|null
     */
    public function getCibles(): ?string
    {
        return $this->cibles;
    }

    /**
     * @param string|null $cibles
     */
    public function setCibles(?string $cibles): void
    {
        $this->cibles = $cibles;
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
    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    /**
     * @param string|null $dateDebut
     */
    public function setDateDebut(?string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return string|null
     */
    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    /**
     * @param string|null $dateFin
     */
    public function setDateFin(?string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return string|null
     */
    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    /**
     * @param string|null $lieu
     */
    public function setLieu(?string $lieu): void
    {
        $this->lieu = $lieu;
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
     * @return int|null
     */
    public function getPrix(): ?int
    {
        return $this->prix;
    }

    /**
     * @param int|null $prix
     */
    public function setPrix(?int $prix): void
    {
        $this->prix = $prix;
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
     * @return bool
     */
    public function isRedactionFinished(): bool
    {
        return $this->redactionFinished;
    }

    /**
     * @param bool $redactionFinished
     */
    public function setRedactionFinished(bool $redactionFinished): void
    {
        $this->redactionFinished = $redactionFinished;
    }

    /**
     * @return int|null
     */
    public function getNombreAchat(): ?int
    {
        return $this->nombreAchat;
    }

    /**
     * @param int|null $nombreAchat
     */
    public function setNombreAchat(?int $nombreAchat): void
    {
        $this->nombreAchat = $nombreAchat;
    }

}

