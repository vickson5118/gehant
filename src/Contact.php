<?php
declare(strict_types = 1);
namespace src;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/utils/Functions.php");

class Contact {
    private ?int $id;
    private ?string $nom;
    private ?string $prenoms;
    private ?string $telephone;
    private ?string $email;
    private ?string $objet;
    private ?string $message;
    private ?string $dateEnvoi;
    private bool $view;
    private Utilisateur $utilisateur;

    /**
     * @return int|null
     */
    public function getId(): ?int{
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void{
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string{
        return $this->nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void{
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenoms(): ?string{
        return $this->prenoms;
    }

    /**
     * @param string|null $prenoms
     */
    public function setPrenoms(?string $prenoms): void{
        $this->prenoms = $prenoms;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string{
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void{
        $this->telephone = $telephone;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string{
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void{
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getObjet(): ?string{
        return $this->objet;
    }

    /**
     * @param string|null $objet
     */
    public function setObjet(?string $objet): void{
        $this->objet = $objet;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string{
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void{
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getDateEnvoi(): ?string{
        return $this->dateEnvoi;
    }

    /**
     * @param string|null $dateEnvoi
     */
    public function setDateEnvoi(?string $dateEnvoi): void{
        $this->dateEnvoi = $dateEnvoi;
    }

    /**
     * @return bool
     */
    public function isView(): bool{
        return $this->view;
    }

    /**
     * @param bool $view
     */
    public function setView(bool $view): void{
        $this->view = $view;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur{
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur): void{
        $this->utilisateur = $utilisateur;
    }


    
    
}

