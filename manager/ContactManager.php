<?php
namespace manager;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Contact.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/Database.php");

use Exception;
use src\Contact;
use utils\Constants;
use utils\Functions;
use src\Entreprise;
use src\Utilisateur;

class ContactManager {

    /**
     * Envoyer un mail pour un particulier
     * @param Contact $contact
     * @return bool
     */
    public function addContact(Contact $contact): bool {
        try {
            $bdd = Database::getInstance();
            $sql = "INSERT INTO " . Constants::TABLE_CONTACTEZ_NOUS . "(nom,prenoms,telephone,email,objet,message,date_envoi)
                VALUES (?,?,?,?,?,?,?)";
            $prepare = Functions::bindPrepare($bdd, $sql,
                $contact->getNom(),
                $contact->getPrenoms(),
                $contact->getTelephone(),
                $contact->getEmail(),
                $contact->getObjet(),
                $contact->getMessage(),
                $contact->getDateEnvoi()
            );

            return $prepare->execute();
        } catch ( Exception $e ) {
            die("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }
    
    /**
     * Envoyer un mail pour un particulier
     * @param Contact $contact
     * @return bool
     */
    public function addEntrepriseContact(Contact $contact): bool {
        try {
            $bdd = Database::getInstance();
            $sql = "INSERT INTO " . Constants::TABLE_CONTACTEZ_NOUS . "(objet,message,date_envoi,utilisateur_id)
                VALUES (?,?,?,?)";
            $prepare = Functions::bindPrepare($bdd, $sql,
                $contact->getObjet(),
                $contact->getMessage(),
                $contact->getDateEnvoi(),
                $contact->getUtilisateur()->getId()
                );
            
            return $prepare->execute();
        } catch ( Exception $e ) {
            die("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }
    
    
    /**
     * Les particuliers nous ayant contacté
     * @return array
     */
    public function getListeParticulierContact(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id,nom,prenoms,telephone,email,objet,date_envoi,view FROM ".Constants::TABLE_CONTACTEZ_NOUS
        	           ." WHERE utilisateur_id IS NULL";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeContact = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $contact = new Contact();
        	    
        	    $contact->setId($result["id"]);
        	    $contact->setNom($result["nom"]);
        	    $contact->setPrenoms($result["prenoms"]);
        	    $contact->setTelephone($result["telephone"]);
        	    $contact->setEmail($result["email"]);
        	    $contact->setObjet($result["objet"]);
        	    $contact->setDateEnvoi(Functions::convertDateEnToFr($result["date_envoi"]));
        	    $contact->setView($result["view"]);
        	    
        	    $listeContact[] = $contact;
        	}
        	
        	return $listeContact;
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Les entreprises nous ayant contacté
     * @return array
     */
    public function getListeEntrepriseContact(): array{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT c.id,u.id AS user_id,u.email,u.telephone,c.objet,c.date_envoi,c.view,e.nom AS entreprise_name 
                    FROM ".Constants::TABLE_CONTACTEZ_NOUS." AS c INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u 
                     ON c.utilisateur_id=u.id INNER JOIN ".Constants::TABLE_ENTREPRISE." AS e ON u.entreprise_id=e.id 
                    WHERE c.utilisateur_id IS NOT NULL";
            
            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();
            
            $listeContact = array();
            
            while ($result = $prepare->fetch()) {
                $contact = new Contact();
                $entreprise = new Entreprise();
                $utilisateur = new Utilisateur();
                
                $entreprise->setNom($result["entreprise_name"]);
                
                $utilisateur->setId($result["user_id"]);
                $utilisateur->setEmail($result["email"]);
                $utilisateur->setTelephone($result["telephone"]);
                $utilisateur->setEntreprise($entreprise);
                
                $contact->setId($result["id"]);
                $contact->setObjet($result["objet"]);
                $contact->setDateEnvoi(Functions::convertDateEnToFr($result["date_envoi"]));
                $contact->setView($result["view"]);
                $contact->setUtilisateur($utilisateur);
                
                $listeContact[] = $contact;
            }
            
            return $listeContact;
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Lire le mail
     * @param int $contactId
     * @return Contact
     */
    public function getMail(int $contactId): Contact{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT c.id,c.nom,c.prenoms,c.email,c.telephone,c.objet,c.message,c.date_envoi,c.view,u.id AS user_id,u.nom AS us_nom,
                        u.prenoms AS us_prenoms,u.email AS us_email,u.telephone AS us_tel FROM ".Constants::TABLE_CONTACTEZ_NOUS
                        ." AS c LEFT JOIN ".Constants::TABLE_UTILISATEUR." AS u ON c.utilisateur_id=u.id WHERE c.id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $contactId);
        	$prepare->execute();
        	
        	$contact = null;
        	while ($result = $prepare->fetch()) {
        	    $contact = new Contact();
        	    $utilisateur = new Utilisateur();
        	    
        	    $utilisateur->setId($result["user_id"]);
        	    $utilisateur->setNom($result["us_nom"]);
        	    $utilisateur->setPrenoms($result["us_prenoms"]);
        	    $utilisateur->setEmail($result["us_email"]);
        	    $utilisateur->setTelephone($result["us_tel"]);
        	    
        	    $contact->setId($result["id"]);
        	    $contact->setNom($result["nom"]);
        	    $contact->setPrenoms($result["prenoms"]);
        	    $contact->setEmail($result["email"]);
        	    $contact->setTelephone($result["telephone"]);
        	    $contact->setObjet($result["objet"]);
        	    $contact->setMessage($result["message"]);
        	    $contact->setDateEnvoi(Functions::convertDateEnToFr($result["date_envoi"]));
        	    $contact->setView($result["view"]);
        	    $contact->setUtilisateur($utilisateur);
        	    
        	}
        
        	return $contact;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Mise à jour de la lecture du mail
     * @param int $id
     */
    public function updateView(int $id): void{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_CONTACTEZ_NOUS." SET view=true WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $id);
        	$prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Verifier s'il y a des mails non lus en BDD
     * @return bool
     */
    public function isNotView(): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT view FROM ".Constants::TABLE_CONTACTEZ_NOUS." WHERE view=false";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
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
    
}





