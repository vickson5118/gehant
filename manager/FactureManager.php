<?php
namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use Exception;
use src\Facture;
use utils\Constants;
use utils\Functions;

class FactureManager{

    /**
     * Obtenir toutes les factures d'un utilisateur
     * @param int $utilisateurOrEntrepriseId
     * @param bool $isEntreprise
     * @return array
     */
    public function getListeFacture(int $utilisateurOrEntrepriseId, bool $isEntreprise): array{
        try {
        	$bdd = Database::getInstance();
        	if($isEntreprise){
        	    $sql = "SELECT fa.designation,fa.prix,fa.date_creation,fa.pdf FROM ".Constants::TABLE_FACTURE
        	    ." AS fa INNER JOIN ".Constants::TABLE_ACHAT." AS a ON a.facture_id=fa.id
                       WHERE a.entreprise_id=? GROUP BY fa.designation";
        	}else{
        	    $sql = "SELECT fa.designation,fa.prix,fa.date_creation,fa.pdf FROM ".Constants::TABLE_FACTURE
        	    ." AS fa INNER JOIN ".Constants::TABLE_ACHAT." AS a ON a.facture_id=fa.id
                       WHERE a.utilisateur_id=? AND a.entreprise_id<=>null";
        	}
        	
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $utilisateurOrEntrepriseId);
        	$prepare->execute();
        	
        	$listeFacture = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $facture = new Facture();
        	    
        	    $facture->setDesignation($result["designation"]);
        	    $facture->setPrix($result["prix"]);
        	    $facture->setDateCreation(Functions::convertDateEnToFr($result["date_creation"]));
        	    $facture->setPdf($result["pdf"]);
        	    
        	    $listeFacture[] = $facture;
        	    
        	}
            
        	return $listeFacture;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Ajouter une formaton acheté en BDD et faire la facture
     * @param Facture $facture
     * @return int
     */
    public function addFacture(Facture $facture): int{
        try {
            
            $bdd = Database::getInstance();
            $sql = "INSERT INTO ".Constants::TABLE_FACTURE."(designation,prix,date_creation,pdf)
                        VALUES(?,?,?,?)";
            
            $prepare = Functions::bindPrepare($bdd, $sql,
                $facture->getDesignation(),
                $facture->getPrix(),
                $facture->getDateCreation(),
                $facture->getPdf()
                );
            $prepare->execute();
            
            return $bdd->lastInsertId();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    
}

