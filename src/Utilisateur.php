<?php

declare(strict_types = 1);

namespace src;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/utils/Functions.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Entreprise.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Pays.php");

class Utilisateur{
    private ?int $id;
    private ?string $nom;
    private ?string $prenoms;
    private ?string $email;
    private ?string $password;
    private ?string $telephone;
    private ?string $dateNaissance;
    private ?string $fonction;
    private ?string $dateInscription;
    private ?string $token;
    private bool $active;
    private bool $connect;
    private TypeCompte $typeCompte;
    private Pays $pays;
    private Entreprise $entreprise;
    private bool $bloquer;
    private ?string $dateBlocage;
    private ?string $motifBlocage;
    private ?string $profilPicture;
    private ?string $derniereConnexion;

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
     * @return string|null
     */
    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    /**
     * @param string|null $prenoms
     */
    public function setPrenoms(?string $prenoms): void
    {
        $this->prenoms = $prenoms;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string|null
     */
    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    /**
     * @param string|null $dateNaissance
     */
    public function setDateNaissance(?string $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @return string|null
     */
    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    /**
     * @param string|null $fonction
     */
    public function setFonction(?string $fonction): void
    {
        $this->fonction = $fonction;
    }

    /**
     * @return string|null
     */
    public function getDateInscription(): ?string
    {
        return $this->dateInscription;
    }

    /**
     * @param string|null $dateInscription
     */
    public function setDateInscription(?string $dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
   * @return bool
   */
   public function isConnect(): bool{
        return $this->connect;
   }

    /**
     * @param bool $connect
     */
    public function setConnect(bool $connect): void
    {
        $this->connect = $connect;
    }

    /**
     * @return TypeCompte
     */
    public function getTypeCompte(): TypeCompte
    {
        return $this->typeCompte;
    }

    /**
     * @param TypeCompte $typeCompte
     */
    public function setTypeCompte(TypeCompte $typeCompte): void
    {
        $this->typeCompte = $typeCompte;
    }

    /**
     * @return Pays
     */
    public function getPays(): Pays
    {
        return $this->pays;
    }

    /**
     * @param Pays $pays
     */
    public function setPays(Pays $pays): void
    {
        $this->pays = $pays;
    }

    /**
     * @return Entreprise
     */
    public function getEntreprise(): Entreprise
    {
        return $this->entreprise;
    }

    /**
     * @param Entreprise $entreprise
     */
    public function setEntreprise(Entreprise $entreprise): void
    {
        $this->entreprise = $entreprise;
    }

    /**
     * @return bool
     */
    public function isBloquer(): bool
    {
        return $this->bloquer;
    }

    /**
     * @param bool $bloquer
     */
    public function setBloquer(bool $bloquer): void
    {
        $this->bloquer = $bloquer;
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
    public function getProfilPicture(): ?string
    {
        return $this->profilPicture;
    }

    /**
     * @param string|null $profilPicture
     */
    public function setProfilPicture(?string $profilPicture): void
    {
        $this->profilPicture = $profilPicture;
    }

    /**
     * @return string|null
     */
    public function getDerniereConnexion(): ?string
    {
       return $this->derniereConnexion;
   }



    /**
     * @param string|null $derniereConnexion
     */
    public function setDerniereConnexion(?string $derniereConnexion): void
    {
        $this->derniereConnexion = $derniereConnexion;
    }


}

