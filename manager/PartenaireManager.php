<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use Exception;
use utils\Functions;
use utils\Constants;
use src\Partenaire;

class PartenaireManager{

    /**
     * Récupérer la liste des partenaires en BDD
     * @return array
     */
    public function getAll() : array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id,nom,logo FROM ".Constants::TABLE_PARTENAIRE;
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listePartenaire = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $partenaire = new Partenaire();
        	    
        	    $partenaire->setId($result["id"]);
        	    $partenaire->setNom($result["nom"]);
        	    $partenaire->setLogo($result["logo"]);
        	    
        	    $listePartenaire[] = $partenaire;
        	}
            
        	return $listePartenaire;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Ajouter un partenaire
     * @param Partenaire $partenaire
     * @return bool
     */
    public function addPartenaire(Partenaire $partenaire): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_PARTENAIRE."(nom,logo) VALUES(?,?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $partenaire->getNom(), $partenaire->getLogo());
        	
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Supprimer un partenaire
     * @param int $id
     * @return bool
     */
    public function removePartenaire(int $id): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "DELETE FROM ".Constants::TABLE_PARTENAIRE." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $id);
        	
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Editer un partenaire sans le logo
     * @param Partenaire $partenaire
     * @return bool
     */
    public function updatePartenaireWithoutLogo(Partenaire $partenaire): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_PARTENAIRE." SET nom=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $partenaire->getNom(),$partenaire->getId());
            
        	return $prepare->execute();
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Editer un partenaire avec le logo
     * @param Partenaire $partenaire
     * @return bool
     */
    public function updatePartenaireWithLogo(Partenaire $partenaire): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE ".Constants::TABLE_PARTENAIRE." SET nom=?,logo=? WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $partenaire->getNom(), $partenaire->getLogo(), $partenaire->getId());
            
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

}

