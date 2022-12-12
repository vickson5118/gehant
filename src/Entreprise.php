<?php

namespace src;

class Entreprise{
    
    private ?int $id;
    private ?string $nom;
    private NombreEmploye $nombreEmploye;
    private Objectif $objectif;
    private Secteur $secteur;
    private Utilisateur $utilisateur;

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
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return NombreEmploye
     */
    public function getNombreEmploye(): NombreEmploye
    {
        return $this->nombreEmploye;
    }

    /**
     * @param NombreEmploye $nombreEmploye
     */
    public function setNombreEmploye(NombreEmploye $nombreEmploye): void
    {
        $this->nombreEmploye = $nombreEmploye;
    }

    /**
     * @return Objectif
     */
    public function getObjectif(): Objectif
    {
        return $this->objectif;
    }

    /**
     * @param Objectif $objectif
     */
    public function setObjectif(Objectif $objectif): void
    {
        $this->objectif = $objectif;
    }

    /**
     * @return Secteur
     */
    public function getSecteur(): Secteur
    {
        return $this->secteur;
    }

    /**
     * @param Secteur $secteur
     */
    public function setSecteur(Secteur $secteur): void
    {
        $this->secteur = $secteur;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }



}

