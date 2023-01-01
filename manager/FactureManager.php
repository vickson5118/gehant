<?php
namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use Exception;
use src\Achat;
use src\Facture;
use src\Utilisateur;
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
        	    $sql = "SELECT fa.designation,fa.prix,fa.date_creation,fa.pdf,fa.proforma FROM ".Constants::TABLE_FACTURE
        	    ." AS fa INNER JOIN ".Constants::TABLE_ACHAT." AS a ON a.facture_id=fa.id
                       WHERE a.entreprise_id=? GROUP BY fa.designation";
        	}else{
        	    $sql = "SELECT fa.designation,fa.prix,fa.date_creation,fa.pdf,fa.proforma FROM ".Constants::TABLE_FACTURE
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
                $facture->setProforma($result["proforma"]);
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

    /** Ajouter la facture proforma à la BDD
     * @param Facture $facture
     * @return int|null
     */
    public function addProfama(Facture $facture): int {
        try {

            $bdd = Database::getInstance ();
            $sql = "INSERT INTO ".Constants::TABLE_FACTURE."(designation,prix,date_proforma,proforma) VALUES(?,?,?,?)";

            $prepare = Functions::bindPrepare ($bdd, $sql,$facture->getDesignation(),$facture->getPrix(),$facture->getDateProforma(),$facture->getProforma());
            $prepare->execute();

            return $bdd->lastInsertId();

        } catch ( Exception $e ) {
            die ( "Une erreur est survenue -> " . $e->getMessage () );
        } finally {
            $prepare->closeCursor ();
        }
    }

    /**Liste des paiements d'utilisateurs en attente
     * @return array
     */
    public function getListeFactureParticulierEnAttente() : array{
        try {

            $bdd = Database::getInstance ();
            $sql = "SELECT a.paid_forced,f.id,f.designation,f.prix,f.date_proforma,u.nom,u.prenoms,u.email,telephone FROM ".Constants::TABLE_FACTURE." as f INNER JOIN "
                .Constants::TABLE_ACHAT." AS a INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u ON a.facture_id=f.id AND a.utilisateur_id=u.id WHERE a.confirm_paid=false GROUP BY f.designation";

            $listeFacture = array();
            $prepare = Functions::bindPrepare ($bdd, $sql);
            $prepare->execute();

            while ($result = $prepare->fetch()){
                $achat = new Achat();
                $facture = new Facture();
                $utilisateur = new Utilisateur();

                $utilisateur->setNom($result["nom"]);
                $utilisateur->setPrenoms($result["prenoms"]);
                $utilisateur->setEmail($result["email"]);
                $utilisateur->setTelephone($result["telephone"]);

                $facture->setId($result["id"]);
                $facture->setDesignation($result["designation"]);
                $facture->setPrix($result["prix"]);
                $facture->setDateProforma(Functions::convertDateEnToFr($result["date_proforma"]));

                $achat->setFacture($facture);
                $achat->setUtilisateur($utilisateur);
                $achat->setPaidForced($result["paid_forced"]);

                $listeFacture[] = $achat;

            }
            return $listeFacture;
        } catch ( Exception $e ) {
            die ( "Une erreur est survenue -> " . $e->getMessage () );
        } finally {
            $prepare->closeCursor ();
        }
    }

    /**Suppression d'une facture
     * @param int $factureId
     * @return bool
     */
    public function deleteFacture(int $factureId) : bool{
        try {

            $bdd = Database::getInstance ();
            $sql = "DELETE FROM ".Constants::TABLE_FACTURE." WHERE id=?";

            $prepare = Functions::bindPrepare ($bdd, $sql,$factureId);
            return $prepare->execute();

        } catch ( Exception $e ) {
            die ( "Une erreur est survenue -> " . $e->getMessage () );
        } finally {
            $prepare->closeCursor ();
        }
    }

    /**MAJ de la facture
     * @param Facture $facture
     * @return bool
     */
    public function updateFacture(Facture $facture) : bool{
        try {

            $bdd = Database::getInstance ();
            $sql = "UPDATE ".Constants::TABLE_FACTURE." SET pdf=?,date_creation=? WHERE id=?";

            $prepare = Functions::bindPrepare ($bdd, $sql,$facture->getPdf(),$facture->getDateCreation(),$facture->getId());
            return $prepare->execute();

        } catch ( Exception $e ) {
            die ( "Une erreur est survenue -> " . $e->getMessage () );
        } finally {
            $prepare->closeCursor ();
        }
    }


}

