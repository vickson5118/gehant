<?php

namespace src;

class NombreEmploye{
    
    private ?int $id;
    private ?string $tranche;

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
    public function getTranche(): ?string
    {
        return $this->tranche;
    }

    /**
     * @param string|null $tranche
     */
    public function setTranche(?string $tranche): void
    {
        $this->tranche = $tranche;
    }


}

