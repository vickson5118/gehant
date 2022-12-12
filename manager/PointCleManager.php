<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/PointCle.php");

use Exception;
use src\PointCle;
use utils\Constants;
use utils\Functions;
use src\Module;

class PointCleManager{
    
    /**
     * Ajouter un point clé
     * @param PointCle $pointCle
     * @return bool
     */
    public function addPointCle(PointCle $pointCle): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_POINT_CLE."(titre,module_id) VALUES(?,?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $pointCle->getTitre(), $pointCle->getModule()->getId());
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Obtenir les points clés d'une formation
     * @param int|null $formationId
     * @return array
     */
    public function getAllPointCleByFormation(?int $formationId): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT p.id,p.titre,p.module_id FROM ".Constants::TABLE_POINT_CLE." AS p INNER JOIN ".
        	           Constants::TABLE_MODULE." AS m INNER JOIN ".Constants::TABLE_FORMATION." AS f ON p.module_id=m.id AND 
                        m.formation_id=f.id WHERE f.id=? ORDER BY p.id";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $formationId);
        	$prepare->execute();
        	
        	$listePointCle = array();
        	while ($donnees = $prepare->fetch()) {
        	    $pointCle = new PointCle();
        	    $module = new Module();
        	    
        	    $module->setId($donnees["module_id"]);
        	    
        	    $pointCle->setId($donnees["id"]);
        	    $pointCle->setTitre($donnees["titre"]);
        	    $pointCle->setModule($module);
        	    
        	    $listePointCle[] = $pointCle;
        	}
            
        	return $listePointCle;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Editer un point cle
     * @param PointCle $pointCle
     * @return bool
     */
    public function editPointCle(PointCle $pointCle): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_POINT_CLE." SET titre=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $pointCle->getTitre(),$pointCle->getId());
            return $prepare->execute();
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    public function deletePointCle(?int $id): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "DELETE FROM ".Constants::TABLE_POINT_CLE." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $id);
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

}

