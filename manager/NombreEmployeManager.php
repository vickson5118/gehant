<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/NombreEmploye.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use Exception;
use utils\Constants;
use utils\Functions;
use src\NombreEmploye;

class NombreEmployeManager{
    
    public function getAll(): array{
        
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id,tranche FROM ".Constants::TABLE_NOMBRE_EMPLOYE." ORDER BY id";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeNombreEmploye = array();
        	
        	while ($donnes = $prepare->fetch()) {
        	    $nombreEmploye = new NombreEmploye();
        	    
        	    $nombreEmploye->setId($donnes["id"]);
        	    $nombreEmploye->setTranche($donnes["tranche"]);
        	    
        	    $listeNombreEmploye[] = $nombreEmploye;
        	}
        
        	return $listeNombreEmploye;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
        
    }
    
    public function isExist(?int $id): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT tranche FROM ".Constants::TABLE_NOMBRE_EMPLOYE." WHERE id=?";
                    
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

}

