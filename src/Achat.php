<?php
declare(strict_types = 1);
namespace src;

class Achat{
    
    private ?int $id;
    private Entreprise $entreprise;
    private Utilisateur $utilisateur;
    private Formation $formation;
    private bool $paid;
    private bool $confirmPaid;
    private Facture $facture;
    private ?string $dateInscription;
    private bool $paidForced;

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
     * @return Entreprise
     */
    public function getEntreprise(): Entreprise{
        return $this->entreprise;
    }

    /**
     * @param Entreprise $entreprise
     */
    public function setEntreprise(Entreprise $entreprise): void{
        $this->entreprise = $entreprise;
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

    /**
     * @return Formation
     */
    public function getFormation(): Formation{
        return $this->formation;
    }

    /**
     * @param Formation $formation
     */
    public function setFormation(Formation $formation): void{
        $this->formation = $formation;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool{
        return $this->paid;
    }

    /**
     * @param bool $paid
     */
    public function setPaid(bool $paid): void{
        $this->paid = $paid;
    }

    /**
     * @return bool
     */
    public function isConfirmPaid(): bool{
        return $this->confirmPaid;
    }

    /**
     * @param bool $confirmPaid
     */
    public function setConfirmPaid(bool $confirmPaid): void{
        $this->confirmPaid = $confirmPaid;
    }

    /**
     * @return Facture
     */
    public function getFacture(): Facture{
        return $this->facture;
    }

    /**
     * @param Facture $facture
     */
    public function setFacture(Facture $facture): void{
        $this->facture = $facture;
    }

    /**
     * @return string|null
     */
    public function getDateInscription(): ?string{
        return $this->dateInscription;
    }

    /**
     * @param string|null $dateInscription
     */
    public function setDateInscription(?string $dateInscription): void{
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return bool
     */
    public function isPaidForced(): bool{
        return $this->paidForced;
    }

    /**
     * @param bool $paidForced
     */
    public function setPaidForced(bool $paidForced): void{
        $this->paidForced = $paidForced;
    }



}

