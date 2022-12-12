<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Domaine.php");

use Exception;
use utils\Constants;
use utils\Functions;
use src\Domaine;
use src\Formation;

class DomaineManager
{

    /**
     * Obtenir toute la liste des domaines enregistrés en BDD
     * @return array
     */
    public function getAll(): array
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,titre,titre_url,description,bloquer,date_blocage,motif_blocage,illustration,nombre_formation_total,
                            nombre_formation_active,nombre_formation_redaction,nombre_formation_bloquer FROM " . Constants::TABLE_DOMAINE;

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();

            $listeDomaine = array();

            while ($result = $prepare->fetch()) {
                $domaine = new Domaine();

                $domaine->setId($result["id"]);
                $domaine->setTitre($result["titre"]);
                $domaine->setTitreUrl($result["titre_url"]);
                $domaine->setDescription($result["description"]);
                $domaine->setBloquer($result["bloquer"]);
                $domaine->setDateBlocage(Functions::convertDateEnToFr($result["date_blocage"]));
                $domaine->setMotifBlocage($result["motif_blocage"]);
                $domaine->setIllustration($result["illustration"]);
                $domaine->setNombreFormationTotal($result["nombre_formation_total"]);
                $domaine->setNombreFormationActive($result["nombre_formation_active"]);
                $domaine->setNombreFormationRedaction($result["nombre_formation_redaction"]);
                $domaine->setNombreFormationBloquer($result["nombre_formation_bloquer"]);

                $listeDomaine[] = $domaine;
            }

            return $listeDomaine;

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Recuperer la liste des domaines sans les domaines bloqués ou supprimés
     * @return array
     */
    public function getAllDomaineWithoutLocked(): array
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,titre,titre_url,illustration FROM " . Constants::TABLE_DOMAINE . " WHERE bloquer=false";

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();

            $listeDomaine = array();

            while ($result = $prepare->fetch()) {
                $domaine = new Domaine();

                $domaine->setId($result["id"]);
                $domaine->setTitre($result["titre"]);
                $domaine->setTitreUrl($result["titre_url"]);
                $domaine->setIllustration($result["illustration"]);

                $listeDomaine[] = $domaine;
            }

            return $listeDomaine;

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Recuperer la liste des 9 derniers domaines
     * @return array
     */
    public function getIndexLastDomaine(): array
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,titre,titre_url,illustration FROM " . Constants::TABLE_DOMAINE . " WHERE bloquer=false 
                        ORDER BY nombre_formation_active DESC LIMIT 0,6";

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();

            $listeDomaine = array();

            while ($result = $prepare->fetch()) {
                $domaine = new Domaine();

                $domaine->setId($result["id"]);
                $domaine->setTitre($result["titre"]);
                $domaine->setTitreUrl($result["titre_url"]);
                $domaine->setIllustration($result["illustration"]);

                $listeDomaine[] = $domaine;
            }

            return $listeDomaine;

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Ajouter un domaine de formation en base de données
     * @param Domaine $domaine
     * @return bool
     */
    public function addDomaine(Domaine $domaine): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "INSERT INTO " . Constants::TABLE_DOMAINE . "(titre,titre_url,description,illustration,date_creation) VALUES(?,?,?,?,?)";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaine->getTitre(), $domaine->getTitreUrl(), $domaine->getDescription(), $domaine->getIllustration(), $domaine->getDateCreation());

            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Verifier si le domaine existe en BDD
     * @param int|null $domaineId
     * @return string|NULL
     */
    public function domaineExist(?int $domaineId): ?string
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT titre_url FROM " . Constants::TABLE_DOMAINE . " WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaineId);
            $prepare->execute();

            $row = $prepare->fetch();

            return $row["titre_url"];

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Ajouter une formation au domaine
     * @param int|null $domaineId
     * @return bool
     */
    public function addFormationToDomaine(?int $domaineId): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET nombre_formation_total=nombre_formation_total+1,
                        nombre_formation_redaction=nombre_formation_redaction+1 WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaineId);
            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Ajouter une formation sur la formation active
     * @param Formation $formation
     * @return bool
     */
    public function updateNombreFormationRedactionAndActive(Formation $formation): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET nombre_formation_redaction=nombre_formation_redaction-1,
                        nombre_formation_active=nombre_formation_active+1 WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $formation->getId());
            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Obtenir les infos sur le domaine s'il n'est pas bloqué
     * @param string|null $domaineUrl
     * @return Domaine|null
     */
    public function getOneDomaine(?string $domaineUrl): ?Domaine
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,titre,titre_url,description,illustration FROM " . Constants::TABLE_DOMAINE . " WHERE titre_url=? AND bloquer=false";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaineUrl);
            $prepare->execute();

            $domaine = null;

            while ($result = $prepare->fetch()) {
                $domaine = new Domaine();

                $domaine->setId($result["id"]);
                $domaine->setTitre($result["titre"]);
                $domaine->setTitreUrl($result["titre_url"]);
                $domaine->setDescription($result["description"]);
                $domaine->setIllustration($result["illustration"]);
            }

            return $domaine;
        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Obtenir une liste de domaine autre que celui en cours
     * @param int|null $domaineId
     * @return array
     */
    public function getOtherDomaineWithoutLocked(?int $domaineId): array
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,titre,titre_url FROM " . Constants::TABLE_DOMAINE . " WHERE id!=? AND bloquer=false";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaineId);
            $prepare->execute();

            $listeDomaine = array();
            while ($result = $prepare->fetch()) {
                $domaine = new Domaine();

                $domaine->setId($result["id"]);
                $domaine->setTitre($result["titre"]);
                $domaine->setTitreUrl($result["titre_url"]);

                $listeDomaine[] = $domaine;
            }

            return $listeDomaine;

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Bloquer un domaine
     * @param Domaine $domaine
     * @return bool
     */
    public function bloquerAndDebloquer(Domaine $domaine): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET bloquer=?,motif_blocage=?,date_blocage=? WHERE id=?";
            $prepare = Functions::bindPrepare($bdd, $sql, $domaine->isBloquer(), $domaine->getMotifBlocage(), $domaine->getDateBlocage(), $domaine->getId());

            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * MAJ du domaine avec limage
     * @param Domaine $domaine
     * @return bool
     */
    public function updateDomaineWithImage(Domaine $domaine): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET titre=?,titre_url=?,description=?,illustration=? WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaine->getTitre(), $domaine->getTitreUrl(), $domaine->getDescription(), $domaine->getIllustration(), $domaine->getId());

            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * MAJ du domaine sans limage
     * @param Domaine $domaine
     * @return bool
     */
    public function updateDomaineWithoutImage(Domaine $domaine): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET titre=?,titre_url=?,description=? WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $domaine->getTitre(), $domaine->getTitreUrl(), $domaine->getDescription(), $domaine->getId());

            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * MAJ du nombre de formations bloquer et active du domaine
     * @param string|null $domaineUrl
     * @param bool $isBloquer
     * @return bool
     */
    public function updateNombreFormationActiveAndBloquer(?string $domaineUrl, bool $isBloquer): bool
    {
        try {
            $bdd = Database::getInstance();

            if ($isBloquer) {
                $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET nombre_formation_active=nombre_formation_active-1,
                        nombre_formation_bloquer=nombre_formation_bloquer+1 WHERE titre_url=?";
            } else {
                $sql = "UPDATE " . Constants::TABLE_DOMAINE . " SET nombre_formation_active=nombre_formation_active+1,
                        nombre_formation_bloquer=nombre_formation_bloquer-1 WHERE titre_url=?";
            }

            $prepare = Functions::bindPrepare($bdd, $sql, $domaineUrl);
            return $prepare->execute();
        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    public function getNombreDomaine(): int
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT COUNT(id) AS nb_domaines FROM " . Constants::TABLE_DOMAINE;

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();
            $row = $prepare->fetch();

            return $row["nb_domaines"];

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

}

