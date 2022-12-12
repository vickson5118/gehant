<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Module.php");

use Exception;
use src\Module;
use utils\Constants;
use utils\Functions;

class ModuleManager{
    
    /**
     * Ajouter un module de formation
     * @param Module $module
     * @return bool
     */
    public function addModule(Module $module): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_MODULE."(titre,formation_id) VALUES(?,?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $module->getTitre(),$module->getFormation()->getId());
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Recuperer la liste des modules disponibles par formation
     * @param int $formationId
     * @return array|NULL
     */
    public function getAllModuleByFormation(int $formationId): ?array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id,titre FROM ".Constants::TABLE_MODULE." WHERE formation_id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $formationId);
        	$prepare->execute();
        	
        	$listeModule = array();
        	
        	while ($donnees = $prepare->fetch()) {
        	    $module = new Module();
        	    
        	    $module->setId($donnees["id"]);
        	    $module->setTitre($donnees["titre"]);
        	    
        	    $listeModule[] = $module;
        	}
        
        	return $listeModule;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Mise à jour du module
     * @param Module $module
     * @return bool
     */
    public function updateModule(Module $module): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_MODULE." SET titre=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $module->getTitre(), $module->getId());
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Supprimer le module
     * @param int|null $moduleId
     * @return bool
     */
    public function deleteModule(?int $moduleId): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "DELETE FROM ".Constants::TABLE_MODULE." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $moduleId);
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

}

