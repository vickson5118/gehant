<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/Database.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use Exception;
use src\Entreprise;
use utils\Constants;
use utils\Functions;
use src\NombreEmploye;
use src\Objectif;
use src\Secteur;

class EntrepriseManager{

    /**
     * Verifier que l'entreprise n'existe pas deja
     * @param string|null $nom
     * @return bool
     */
    public function isExist(?string $nom):bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id FROM ".Constants::TABLE_ENTREPRISE." WHERE nom=?";
                    
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
     * Verifier que le nouveau nom de l'entreprise n'existe pas deja
     * @param string|null $nom
     * @param int $id
     * @return bool
     */
    public function newNameisExist(?string $nom, int $id):bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id FROM ".Constants::TABLE_ENTREPRISE." WHERE nom=? AND id!=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $nom,$id);
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
     * Ajouter une entreprise ne base de données
     * @param Entreprise $entreprise
     * @return int|NULL
     */
    public function addEntreprise(Entreprise $entreprise): ?int{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_ENTREPRISE."(nom,nombre_employe_id,objectif_id,secteur_id) VALUES(?,?,?,?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, 
        	    $entreprise->getNom(),
        	    $entreprise->getNombreEmploye()->getId(),
        	    $entreprise->getObjectif()->getId(),
        	    $entreprise->getSecteur()->getId()
        	    );
        	
        	if($prepare->execute()){
        	    return $bdd->lastInsertId();
        	}
        	
        	return null;
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * MAJ des infos d'une entreprise
     * @param Entreprise $entreprise
     * @return bool
     */
    public function updateEntrepriseInfos(Entreprise $entreprise): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_ENTREPRISE." SET nom=?,nombre_employe_id=?,objectif_id=?,secteur_id=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, 
        	    $entreprise->getNom(),
        	    $entreprise->getNombreEmploye()->getId(),
        	    $entreprise->getObjectif()->getId(),
        	    $entreprise->getSecteur()->getId(),
        	    $entreprise->getId()
        	    );
        	
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Nombre des entreprises en BDD
     * @return int
     */
    public function getNombreEntreprise(): int{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT COUNT(id) AS nb_entreprises FROM ".Constants::TABLE_ENTREPRISE;
            
            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();
            
            $row = $prepare->fetch();
            
            return $row["nb_entreprises"];
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Toutes les entreprises de la BDD
     * @return array
     */
    public function getAll(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT e.id,e.nom,nb.tranche,o.nom AS obj_name,s.nom AS sect_name FROM ".Constants::TABLE_ENTREPRISE
            	   ." AS e INNER JOIN ".Constants::TABLE_NOMBRE_EMPLOYE." AS nb ON e.nombre_employe_id=nb.id INNER JOIN ".
        	           Constants::TABLE_OBJECTIF." AS o ON e.objectif_id=o.id INNER JOIN ".Constants::TABLE_SECTEUR
        	           ." AS s ON e.secteur_id=s.id";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeEntreprise = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $entreprise = new Entreprise();
        	    $nombreEmploye = new NombreEmploye();
        	    $objectif = new Objectif();
        	    $secteur = new Secteur();
        	    
        	    $nombreEmploye->setTranche($result["tranche"]);
        	    
        	    $objectif->setNom($result["obj_name"]);
        	    
        	    $secteur->setNom($result["sect_name"]);
        	    
        	    $entreprise->setId($result["id"]);
        	    $entreprise->setNom($result["nom"]);
        	    $entreprise->setNombreEmploye($nombreEmploye);
        	    $entreprise->setObjectif($objectif);
        	    $entreprise->setSecteur($secteur);
        	    
        	    $listeEntreprise[] = $entreprise;
        	    
        	}
            
        	return $listeEntreprise;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Obtenir le nom de l'entreprise
     * @param int|null $entrepriseId
     * @return string|NULL
     */
    public function getName(?int $entrepriseId): ?string{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT nom FROM ".Constants::TABLE_ENTREPRISE." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $entrepriseId);
        	$prepare->execute();
        	$row = $prepare->fetch();
        	
        	return $row["nom"];
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Mise à jour de l'id de l'utilisateur de l'entreprise
     * @param int $utilisateurId
     * @param int $entrepriseId
     */
    public function updateEntrepriseUtilisateur(int $utilisateurId, int $entrepriseId): void{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_ENTREPRISE." SET utilisateur_id=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $utilisateurId, $entrepriseId);
        	$prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

}

