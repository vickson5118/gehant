<?php

namespace manager;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");

use Exception;
use utils\Constants;
use utils\Functions;
use src\Formation;
use src\Domaine;
use src\Utilisateur;

class FormationManager{
    
    /**
     * Recuperer toutes les formations en base de données
     * @return array
     */
    public function getAll(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT f.id,f.titre,f.titre_url,f.bloquer,f.redaction_finished,f.date_creation,f.date_debut,f.date_fin,
                        f.motif_blocage,f.date_blocage,f.nombre_achat,d.titre AS domaine_titre,
                       d.titre_url AS domaine_titre_url,u.email FROM ".Constants::TABLE_FORMATION." AS f 
                       INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d 
                       ON f.auteur_id=u.id AND f.domaine_id=d.id";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeFormation = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $formation = new Formation();
        	    $domaine = new Domaine();
        	    $auteur = new Utilisateur();
        	    
        	    $domaine->setTitre($result["domaine_titre"]);
        	    $domaine->setTitreUrl($result["domaine_titre_url"]);
        	    
        	    $auteur->setEmail($result["email"]);
        	    
        	    $formation->setId($result["id"]);
        	    $formation->setTitre($result["titre"]);
        	    $formation->setTitreUrl($result["titre_url"]);
        	    $formation->setBloquer($result["bloquer"]);
        	    $formation->setRedactionFinished($result["redaction_finished"]);
        	    $formation->setDateCreation(Functions::convertDateEnToFr($result["date_creation"] ));
        	    $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"] ));
        	    $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"] ));
        	    $formation->setMotifBlocage($result["motif_blocage"]);
        	    $formation->setDateBlocage(Functions::convertDateEnToFr($result["date_blocage"]));
        	    $formation->setNombreAchat($result["nombre_achat"]);
        	    $formation->setDomaine($domaine);
        	    $formation->setAuteur($auteur);
        	    
        	    $listeFormation[] = $formation;
        	}
            
        	return $listeFormation;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Ajouter une formation
     * @param Formation $formation
     * @return bool
     */
    public function addFormation(Formation $formation): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_FORMATION."(titre,titre_url,date_creation,domaine_id,auteur_id) VALUES(?,?,?,?,?)";
        	$prepare = Functions::bindPrepare($bdd, $sql, 
        	    $formation->getTitre(),
        	    $formation->getTitreUrl(),
        	    $formation->getDateCreation(),
        	    $formation->getDomaine()->getId(),
        	    $formation->getAuteur()->getId()
        	    );
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Verifie que le titre existe
     * @param string|null $titre
     * @return boolean
     */
    public function titreExist(?string $titre) : bool{
       try {
       	$bdd = Database::getInstance();
       	$sql = "SELECT id FROM ".Constants::TABLE_FORMATION." WHERE titre=?";
                   
       	$prepare = Functions::bindPrepare($bdd, $sql, $titre);
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
     * Verifie que le titre changé n'existe pas en base de donnees
     * @param string|null $titre
     * @param int|null $formationId
     * @return boolean
     */
    public function titreChangeExist(?string $titre, ?int $formationId) : bool{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id FROM ".Constants::TABLE_FORMATION." WHERE titre=? AND id!=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $titre,$formationId);
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
     * Obtenir les informations d'une formation tq les titres, les prerequis, les cibles, etc
     * @param string|null $domaineUrl
     * @param string|null $titreUrl
     * @param bool $isAdmin
     * @return Formation|NULL
     */
    public function getOneFormationInfo(?string $domaineUrl,?string $titreUrl, bool $isAdmin): ?Formation{
        try {
        	$bdd = Database::getInstance();
        	if($isAdmin){
        	    $sql = "SELECT f.id,f.titre,f.titre_url,f.but,f.objectifs,f.prerequis,f.cibles,f.description,f.prix,f.illustration,
                        f.date_debut,f.date_fin,f.lieu,f.redaction_finished,f.bloquer,u.id AS auteurId,u.prenoms,u.nom,d.id AS domaine_id,
                        d.titre AS domaine_titre,d.titre_url AS domaine_titre_url FROM ".Constants::TABLE_FORMATION
                        ." AS f INNER JOIN ". Constants::TABLE_UTILISATEUR." AS u INNER JOIN ".Constants::TABLE_DOMAINE
                        ." AS d ON f.auteur_id=u.id AND f.domaine_id = d.id WHERE d.titre_url=? AND f.titre_url=?";
        	}else{
        	    $sql = "SELECT f.id,f.titre,f.titre_url,f.but,f.objectifs,f.prerequis,f.cibles,f.description,f.prix,f.illustration,
                        f.date_debut,f.date_fin,f.lieu,f.redaction_finished,f.bloquer,u.id AS auteurId,u.prenoms,u.nom,d.id AS domaine_id,
                        d.titre AS domaine_titre,d.titre_url AS domaine_titre_url FROM ".Constants::TABLE_FORMATION
                        ." AS f INNER JOIN ". Constants::TABLE_UTILISATEUR." AS u INNER JOIN ".Constants::TABLE_DOMAINE
                        ." AS d ON f.auteur_id=u.id AND f.domaine_id = d.id WHERE d.titre_url=? AND f.titre_url=? AND f.bloquer=false 
                            AND d.bloquer=false AND f.date_debut >NOW()";
        	}
            
            $prepare = Functions::bindPrepare($bdd, $sql,$domaineUrl, $titreUrl);
        	$prepare->execute();
        	
        	$formation = null;
        	
        	while ($result = $prepare->fetch()) {
        	    
        	    $formation = new Formation();
        	    $utilisateur = new Utilisateur();
        	    $domaine = new Domaine();
        	    
        	    
        	    $domaine->setId($result["domaine_id"]);
        	    $domaine->setTitre($result["domaine_titre"]);
        	    $domaine->setTitreUrl($result["domaine_titre_url"]);
        	    
        	    $utilisateur->setId($result["auteurId"]);
        	    $utilisateur->setNom($result["nom"]);
        	    $utilisateur->setPrenoms($result["prenoms"]);
        	    
        	    $formation->setId($result["id"]);
        	    $formation->setTitre($result["titre"]);
        	    $formation->setTitreUrl($result["titre_url"]);
        	    $formation->setDescription($result["description"]);
        	    $formation->setObjectifs($result["objectifs"]);
        	    $formation->setCibles($result["cibles"]);
        	    $formation->setBut($result["but"]);
        	    $formation->setPrix($result["prix"]);
        	    $formation->setRedactionFinished($result["redaction_finished"]);
        	    $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
        	    $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
        	    $formation->setLieu($result["lieu"]);
        	    $formation->setPrerequis($result["prerequis"]);
        	    $formation->setIllustration($result["illustration"]);
        	    $formation->setBloquer($result["bloquer"]);
        	    $formation->setDomaine($domaine);
        	    $formation->setAuteur($utilisateur);
        	}
            
        	return $formation;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Mise à jour des prerequis de la formation
     * @param Formation $formation
     * @return bool
     */
    public function updateFormationPrerequis(Formation $formation): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_FORMATION." SET prerequis = ? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, 
        	    $formation->getPrerequis(),
        	    $formation->getId()
        	    );
        	
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Mise à jour des cibles de la formation
     * @param Formation $formation
     * @return bool
     */
    public function updateFormationCibles(Formation $formation): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE ".Constants::TABLE_FORMATION." SET cibles = ? WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql,
                $formation->getCibles(),
                $formation->getId()
                );
            
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Mise à jour des objectifs
     * @param Formation $formation
     * @return bool
     */
    public function updateFormationObjectifs(Formation $formation): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE ".Constants::TABLE_FORMATION." SET objectifs = ? WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql,
                $formation->getObjectifs(),
                $formation->getId()
                );
            
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Ajouter les infos de base de la formation avec limage d'illustration
     * @param Formation $formation
     * @return bool
     */
    public function updateBasicsWithImageFile(Formation $formation): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_FORMATION." SET titre=?,titre_url=?,but=?,domaine_id=?,prix=?,description=?
                        ,illustration=?,lieu=?,date_debut=?,date_fin=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, 
        	    $formation->getTitre(),
        	    $formation->getTitreUrl(),
        	    $formation->getBut(),
        	    $formation->getDomaine()->getId(),
        	    $formation->getPrix(),
        	    $formation->getDescription(),
        	    $formation->getIllustration(),
        	    $formation->getLieu(),
        	    $formation->getDateDebut(),
        	    $formation->getDateFin(),
        	    $formation->getId()
        	    );
            
        	return $prepare->execute();
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Ajouter les infos de base de la formation sans limage d'illustration
     * @param Formation $formation
     * @return bool
     */
    public function updateBasicsWithoutImageFile(Formation $formation): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE ".Constants::TABLE_FORMATION." SET titre=?,titre_url=?,but=?,domaine_id=?,prix=?,description=?,
                            lieu=?,date_debut=?,date_fin=? WHERE id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql,
                $formation->getTitre(),
                $formation->getTitreUrl(),
                $formation->getBut(),
                $formation->getDomaine()->getId(),
                $formation->getPrix(),
                $formation->getDescription(),
                $formation->getLieu(),
                $formation->getDateDebut(),
                $formation->getDateFin(),
                $formation->getId()
                );
            
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Valider la creation de la formation
     * @param Formation $formation
     * @return bool
     */
    public function redactionFinished(Formation $formation): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "UPDATE ".Constants::TABLE_FORMATION." SET redaction_finished=true,date_creation=? WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $formation->getDateCreation(), $formation->getId());
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Obtenir les formations d'un domaine sans les formations bloquer ou supprimer
     * @param int|null $domaineId
     * @return array
     */
    public function getFormationByDomaineInfoWhithoutLocked(?int $domaineId): array{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,titre,titre_url,prix,lieu,date_debut,date_fin FROM ".Constants::TABLE_FORMATION." WHERE domaine_id=? AND
                            date_debut>NOW() AND redaction_finished=true AND bloquer=false";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $domaineId);
            $prepare->execute();
            
            $listeFormation = array();
            
            while ($result = $prepare->fetch()) {
                $formation = new Formation();
                
                $formation->setId($result["id"]);
                $formation->setTitre($result["titre"]);
                $formation->setTitreUrl($result["titre_url"]);
                $formation->setPrix($result["prix"]);
                $formation->setLieu($result["lieu"]);
                $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                
                $listeFormation[] = $formation;
            }
            
            return $listeFormation;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Obtenir les 3 dernières formations
     * @return array
     */
    public function getFourLastFormation(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT f.titre,f.titre_url,f.illustration,d.titre_url AS domaine_titre_url FROM ".Constants::TABLE_FORMATION
            	." AS f INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON f.domaine_id=d.id WHERE f.date_debut>NOW() AND
                 f.redaction_finished = true AND f.bloquer = false AND d.bloquer=false ORDER BY f.date_creation DESC LIMIT 0,4";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
     
        	$listeFormation = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $formation = new Formation();
        	    $domaine = new Domaine();
        	    $auteur = new Utilisateur();
        	    
        	    $domaine->setTitreUrl($result["domaine_titre_url"]);
        	    
        	    $auteur->setNom($result["nom"]);
        	    $auteur->setPrenoms($result["prenoms"]);
        	    
        	    $formation->setId($result["id"]);
        	    $formation->setTitre($result["titre"]);
        	    $formation->setTitreUrl($result["titre_url"]);
        	    $formation->setIllustration($result["illustration"]);
        	    $formation->setDescription($result["description"]);
        	    $formation->setDomaine($domaine);
        	    $formation->setAuteur($auteur);
        	    
        	    $listeFormation[] = $formation;
        	}
        
        	return $listeFormation;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Obtenir les 12 dernieres formations sans la formation courante
     * @param int|null $id
     * @return array
     */
    public function getTwelveLastFormationWithoutThis(?int $id): array{
        try {
            
            $bdd = Database::getInstance();
            $sql = "SELECT f.titre,f.titre_url,f.illustration,f.date_debut,f.date_fin,d.titre_url AS domaine_titre_url,u.nom,u.prenoms 
                FROM ".Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_DOMAINE." AS d INNER JOIN ".
                Constants::TABLE_UTILISATEUR." AS u ON f.domaine_id=d.id AND f.auteur_id = u.id WHERE f.id!=? AND 
            f.date_debut>NOW() AND f.redaction_finished = true AND f.bloquer = false ORDER BY f.date_creation LIMIT 0,12";
           
                $prepare = Functions::bindPrepare($bdd, $sql, $id);
                $prepare->execute();
                
                $listeFormation = array();
                
                while ($result = $prepare->fetch()) {
                    $formation = new Formation();
                    $domaine = new Domaine();
                    $auteur = new Utilisateur();
                    
                    $domaine->setTitreUrl($result["domaine_titre_url"]);
                    
                    $auteur->setNom($result["nom"]);
                    $auteur->setPrenoms($result["prenoms"]);
                    
                    $formation->setTitre($result["titre"]);
                    $formation->setTitreUrl($result["titre_url"]);
                    $formation->setIllustration($result["illustration"]);
                    $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                    $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                    $formation->setDomaine($domaine);
                    $formation->setAuteur($auteur);
                    
                    $listeFormation[] = $formation;
                }
                
                return $listeFormation;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Obtenir les 12 dernieres formations
     * @return array
     */
    public function getTwelveLastFormation(): array{
        try {
            
            $bdd = Database::getInstance();
            $sql = "SELECT f.titre,f.titre_url,f.illustration,f.date_debut,f.date_fin,d.titre_url AS domaine_titre_url,u.nom,u.prenoms
                FROM ".Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_DOMAINE." AS d INNER JOIN ".
                Constants::TABLE_UTILISATEUR." AS u ON f.domaine_id=d.id AND f.auteur_id = u.id WHERE f.date_debut>NOW() 
                AND f.redaction_finished = true AND f.bloquer = false ORDER BY f.date_creation LIMIT 0,12";
                
                $prepare = Functions::bindPrepare($bdd, $sql);
                $prepare->execute();
                
                $listeFormation = array();
                
                while ($result = $prepare->fetch()) {
                    $formation = new Formation();
                    $domaine = new Domaine();
                    $auteur = new Utilisateur();
                    
                    $domaine->setTitreUrl($result["domaine_titre_url"]);
                    
                    $auteur->setNom($result["nom"]);
                    $auteur->setPrenoms($result["prenoms"]);
                    
                    $formation->setTitre($result["titre"]);
                    $formation->setTitreUrl($result["titre_url"]);
                    $formation->setIllustration($result["illustration"]);
                    $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                    $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                    $formation->setDomaine($domaine);
                    $formation->setAuteur($auteur);
                    
                    $listeFormation[] = $formation;
                }
                
                return $listeFormation;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Les 4 dernières formations les plus vendus
     * @return array
     */
    public function getFourMostSold(): array{
        try {
            
            $bdd = Database::getInstance();
            $sql = "SELECT f.titre,f.titre_url,f.illustration,d.titre_url AS domaine_titre_url FROM ".Constants::TABLE_FORMATION
                ." AS f INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON f.domaine_id=d.id WHERE f.date_debut>NOW() AND
            f.redaction_finished = true AND f.bloquer = false AND d.bloquer=false ORDER BY f.nombre_achat DESC LIMIT 0,4";
            
            
            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();
            
            $listeFormation = array();
            
            while ($result = $prepare->fetch()) {
                $formation = new Formation();
                $domaine = new Domaine();
                
                $domaine->setTitreUrl($result["domaine_titre_url"]);
 
                $formation->setTitre($result["titre"]);
                $formation->setTitreUrl($result["titre_url"]);
                $formation->setIllustration($result["illustration"]);
                $formation->setDomaine($domaine);
                
                $listeFormation[] = $formation;
            }
            
            return $listeFormation;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Obtenir les infos d'une formation pour la facture
     * @param int $id
     * @return Formation|NULL
     */
    public function getOneFormationInfoById(int $id): ?Formation{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT f.id,f.titre,f.titre_url,f.prix,f.date_debut,f.date_fin,f.lieu,u.prenoms,u.nom,d.titre_url AS domaineUrl FROM "
                        .Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u ON f.auteur_id=u.id 
                        INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON f.domaine_id=d.id WHERE f.id=?";
                    $prepare = Functions::bindPrepare($bdd, $sql, $id);
                    $prepare->execute();
                    
                    $formation = null;
                    
                    while ($result = $prepare->fetch()) {
                        
                        $formation = new Formation();
                        $utilisateur = new Utilisateur();
                        $domaine = new Domaine();
                        
                        $utilisateur->setNom($result["nom"]);
                        $utilisateur->setPrenoms($result["prenoms"]);
                        
                        $domaine->setTitreUrl($result["domaineUrl"]);
                        
                        $formation->setId($result["id"]);
                        $formation->setTitre($result["titre"]);
                        $formation->setTitreUrl($result["titre_url"]);
                        $formation->setPrix($result["prix"]);
                        $formation->setLieu($result["lieu"]);
                        $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                        $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                        $formation->setAuteur($utilisateur);
                        $formation->setDomaine($domaine);
                    }
                    
                    return $formation;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Rechercher une formation
     * @param string $search
     * @return array
     */
    public function searchFormation(string $search): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT f.titre,f.titre_url,d.titre_url AS domaine_url FROM ".Constants::TABLE_FORMATION." AS f INNER JOIN ".
        	           Constants::TABLE_DOMAINE." AS d ON f.domaine_id=d.id WHERE MATCH(f.titre) AGAINST('".$search."*"
        	           ."' IN BOOLEAN MODE) AND f.redaction_finished=true AND f.bloquer=false";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $search);
        	$prepare->execute();
        	
        	$listeFormation = array();
        	
        	while ($result = $prepare->fetch()) {
        	  
        	   $formation = array(
        	       "titre" => $result["titre"],
        	       "titreUrl" => $result["titre_url"],
        	       "domaineUrl" => $result["domaine_url"],
        	   );
        	   
        	   $listeFormation[] = $formation;
        	}
            
        	return $listeFormation;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Recuperer toutes les formations en base de données d'un domaine
     * @param string|null $domaineUrl
     * @return array
     */
    public function getAllFormationByDomaine(?string $domaineUrl): array{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT f.id,f.titre,f.titre_url,f.bloquer,f.redaction_finished,f.date_creation,f.date_debut,f.date_fin,
                        f.motif_blocage,f.date_blocage,f.nombre_achat,d.titre AS domaine_titre,
                       d.titre_url AS domaine_titre_url,u.email FROM ".Constants::TABLE_FORMATION." AS f
                       INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d
                       ON f.auteur_id=u.id AND f.domaine_id=d.id WHERE d.titre_url=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql,$domaineUrl);
            $prepare->execute();
            
            $listeFormation = array();
            
            while ($result = $prepare->fetch()) {
                $formation = new Formation();
                $domaine = new Domaine();
                $auteur = new Utilisateur();
                
                $domaine->setTitre($result["domaine_titre"]);
                $domaine->setTitreUrl($result["domaine_titre_url"]);
                
                $auteur->setEmail($result["email"]);
                
                $formation->setId($result["id"]);
                $formation->setTitre($result["titre"]);
                $formation->setTitreUrl($result["titre_url"]);
                $formation->setBloquer($result["bloquer"]);
                $formation->setRedactionFinished($result["redaction_finished"]);
                $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                $formation->setDateCreation(Functions::convertDateEnToFr($result["date_creation"]));
                $formation->setMotifBlocage($result["motif_blocage"]);
                $formation->setDateBlocage(Functions::convertDateEnToFr($result["date_blocage"]));
                $formation->setNombreAchat($result["nombre_achat"]);
                $formation->setDomaine($domaine);
                $formation->setAuteur($auteur);
                
                $listeFormation[] = $formation;
            }
            
            return $listeFormation;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * Bloquer et debloquer une formation
     * @param Formation $formation
     * @return bool
     */
    public function bloquerAndDebloquer(Formation $formation): bool{
        try {
            $bdd = Database::getInstance();
                $sql = "UPDATE ".Constants::TABLE_FORMATION." SET bloquer=?,motif_blocage=?,date_blocage=? WHERE id=?";
                $prepare = Functions::bindPrepare($bdd, $sql, $formation->isBloquer(), $formation->getMotifBlocage(), $formation->getDateBlocage(), $formation->getId());

            
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * MAJ du nombre d'achats
     * @param int|null $formationId
     * @param string $formationTitre
     */
    public function updateNombreAchat(string $formationTitre, int $formationId = null): void{
        try {
        	$bdd = Database::getInstance();

        	if($formationId != null){
        	    $sql = "UPDATE ".Constants::TABLE_FORMATION." SET nombre_achat=nombre_achat+1 WHERE id=?";
        	    $prepare = Functions::bindPrepare($bdd, $sql, $formationId);
        	}else{
        	    $sql = "UPDATE ".Constants::TABLE_FORMATION." SET nombre_achat=nombre_achat+1 WHERE titre=?";
        	    $prepare = Functions::bindPrepare($bdd, $sql, $formationTitre);
        	}

        	$prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Nombre de formations en BDD
     * @return int
     */
    public function getNombreFormation(): int{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT COUNT(id) AS nb_formations FROM ".Constants::TABLE_FORMATION;
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$row = $prepare->fetch();
        	
        	return $row["nb_formations"];
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Obtenir le titre de la formation
     * @param int|null $formationId
     * @return string
     */
    public function getFormationTitre(?int $formationId): string{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT titre FROM ".Constants::TABLE_FORMATION." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $formationId);
        	$prepare->execute();
        	$row = $prepare->fetch();
        	
        	return $row["titre"];
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

}

