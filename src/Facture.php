<?php
declare(strict_types=1);

namespace src;

class Facture
{

    private ?int $id;
    private ?string $designation;
    private ?int $prix;
    private ?string $dateCreation;
    private ?string $dateProforma;
    private ?string $proforma;

    private ?string $pdf;

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
    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    /**
     * @param string|null $designation
     */
    public function setDesignation(?string $designation): void
    {
        $this->designation = $designation;
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
    public function getDateProforma(): ?string
    {
        return $this->dateProforma;
    }

    /**
     * @param string|null $dateProforma
     */
    public function setDateProforma(?string $dateProforma): void
    {
        $this->dateProforma = $dateProforma;
    }


    /**
     * @return string|null
     */
    public function getProforma(): ?string
    {
        return $this->proforma;
    }

    /**
     * @param string|null $proforma
     */
    public function setProforma(?string $proforma): void
    {
        $this->proforma = $proforma;
    }

    /**
     * @return string|null
     */
    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    /**
     * @param string|null $pdf
     */
    public function setPdf(?string $pdf): void
    {
        $this->pdf = $pdf;
    }


}

