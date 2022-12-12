<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Objectif.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use Exception;
use utils\Constants;
use utils\Functions;
use src\Objectif;

class ObjectifManager{
    
    public function getAll(): array{
        
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id, nom FROM ".Constants::TABLE_OBJECTIF." ORDER BY id";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeObjectif = array();
        	
        	while ($donnees = $prepare->fetch()) {
        	    $objectif = new Objectif();
        	    
        	    $objectif->setId($donnees["id"]);
        	    $objectif->setNom($donnees["nom"]);
        	    
        	    $listeObjectif[] = $objectif;
        	}
            
        	return $listeObjectif;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
        
    }

    /**
     * Verifier si l'objectif existe en BDD
     * @param int|null $id
     * @return bool
     */
    public function isExist(?int $id): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT nom FROM ".Constants::TABLE_OBJECTIF." WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $id);
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
     * Ajouter un nouvel objectif et retourner l'id
     * @param Objectif $objectif
     * @return int|NULL
     */
    public function addObjectif(Objectif $objectif): ?int{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_OBJECTIF."(nom) VALUES(?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $objectif->getNom());
        	$prepare->execute();
        	
        	return $bdd->lastInsertId();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Verifie si le nom de l'objecttif existe deja
     * @param string|null $nom
     * @return bool
     */
    public function isNomExist(?string $nom): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id FROM ".Constants::TABLE_OBJECTIF." WHERE nom=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $nom);
            $prepare->execute();
            
            if(!empty($prepare->fetch())){
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * Verifie si le nom du secteur existe deja sauf le nom en parametres
     * @param string|null $nom
     * @param int|null $id
     * @return bool
     */
    public function isNomExistWithoutThis(?string $nom, ?int $id): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id FROM ".Constants::TABLE_OBJECTIF." WHERE nom=? AND id!=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $nom, $id);
            $prepare->execute();
            
            if(!empty($prepare->fetch())){
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * MAJ du secteur d'activité en BDD
     * @param Objectif $objectif
     * @return bool
     */
    public function updateObjectif(Objectif $objectif): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE ".Constants::TABLE_OBJECTIF." SET nom=? WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $objectif->getNom(), $objectif->getId());
            
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

}

