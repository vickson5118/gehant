<?php

namespace manager;

use Exception;
use utils\Constants;
use utils\Functions;
use src\Pays;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Pays.php");

class PaysManager{
    
    /**
     * Recuperer tous les pays en base de donnees
     * @return array
     */
    public function getAllPays(): array{
        
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,nom FROM ".Constants::TABLE_PAYS;
            
            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();
            
            $listePays = array();
            
            while ($donnees = $prepare->fetch()) {
               $pays = new Pays();
               
               $pays->setId($donnees["id"]);
               $pays->setNom($donnees["nom"]);
               
               $listePays[] = $pays;
            }
            
            return $listePays;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally {
            $prepare->closeCursor();
        }
        
    }

    /**
     * Verifier si l'id du pays exist en BDD
     * @param int|null $paysId
     * @return bool
     */
    public function paysExist(?int $paysId): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT nom FROM ".Constants::TABLE_PAYS." WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $paysId);
            $prepare->execute();
            
            if(empty($prepare->fetch())){
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * Verffier si le nom du pays existe deja en BDD
     * @param string|null $nom
     * @return bool
     */
    public function paysExistNom(?string $nom): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id FROM ".Constants::TABLE_PAYS." WHERE nom=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $nom);
            $prepare->execute();
            
            if(empty($prepare->fetch())){
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Ajouter un pays
     * @param Pays $pays
     * @return bool
     */
    public function addPays(Pays $pays): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_PAYS."(nom) VALUES(?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $pays->getNom());
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Modifier le pays
     * @param Pays $pays
     * @return bool
     */
    public function editPays(Pays $pays): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_PAYS." SET nom=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $pays->getNom(),$pays->getId());
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Supprimer un pays
     * @param int|null $paysId
     * @return bool
     */
    public function deletePays(?int $paysId): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "DELETE FROM ".Constants::TABLE_PAYS." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $paysId);
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Nombre de pays en BDD
     * @return int
     */
    public function getNombrePays(): int{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT COUNT(id) AS nb_pays FROM ".Constants::TABLE_PAYS;
            
            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();
            
            $row = $prepare->fetch();
            
            return $row["nb_pays"];
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

}

