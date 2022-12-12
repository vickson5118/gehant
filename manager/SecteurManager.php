<?php

namespace manager;

use Exception;
use utils\Constants;
use utils\Functions;
use src\Secteur;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Secteur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");

class SecteurManager{
    
    public function getAll(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id,nom FROM ".Constants::TABLE_SECTEUR." ORDER BY id";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeSecteur = array();
        	
        	while ($donnees = $prepare->fetch()) {
        	    $secteur = new Secteur();

        	    $secteur->setId($donnees["id"]);
        	    $secteur->setNom($donnees["nom"]);
        	    
        	    $listeSecteur[] = $secteur;
        	    
        	}
        
        	return $listeSecteur;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Verifier si le secteur exist en BDD
     * @param int|null $id
     * @return bool
     */
    public function isExist(?int $id): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT nom FROM ".Constants::TABLE_SECTEUR." WHERE id=?";
            
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
     * Enregistrer un nouveau secteur d'activité
     * @param Secteur $secteur
     * @return Secteur|NULL
     */
    public function addSecteur(Secteur $secteur): ?int{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_SECTEUR."(nom) VALUES(?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $secteur->getNom());
        	$prepare->execute();
        
        	return $bdd->lastInsertId();
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Verifie si le nom du secteur existe deja
     * @param string|null $nom
     * @return bool
     */
    public function isNomExist(?string $nom): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id FROM ".Constants::TABLE_SECTEUR." WHERE nom=?";
                    
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
            $sql = "SELECT id FROM ".Constants::TABLE_SECTEUR." WHERE nom=? AND id!=?";
            
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
     * @param Secteur $secteur
     * @return bool
     */
    public function updateSecteur(Secteur $secteur): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_SECTEUR." SET nom=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $secteur->getNom(), $secteur->getId());
        	
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

}

