<?php

namespace manager;

use Exception;
use utils\Constants;
use utils\Functions;
use src\Utilisateur;
use src\Entreprise;
use src\Formation;
use src\Achat;
use src\Domaine;

class AchatManager{
    
    /**
     * Ajouter une formation au panier
     * @param Achat $achat
     * @return bool
     */
    public function addFormation(Achat $achat): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "INSERT INTO ".Constants::TABLE_ACHAT."(entreprise_id,utilisateur_id,formation_id) VALUES(?,?,?)";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql,
        	    $achat->getEntreprise()->getId(),
        	    $achat->getUtilisateur()->getId(),
        	    $achat->getFormation()->getId()
        	    );
            return $prepare->execute();
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Ajouter une formation au panier pour un particulier
     * @param Achat $achat
     * @return bool
     */
    public function addFormationParticulier(Achat $achat): bool{
        try {
            $bdd = Database::getInstance();
            $sql = "INSERT INTO ".Constants::TABLE_ACHAT."(utilisateur_id,formation_id) VALUES(?,?)";
            
            $prepare = Functions::bindPrepare($bdd, $sql,
                $achat->getUtilisateur()->getId(),
                $achat->getFormation()->getId(),
                );
            return $prepare->execute();
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Liste des formations présentes dans le panier et dont le panier n'a pas encore été confirmé
     * @param int $entrepriseId
     * @return array
     */
    public function getListeFormationNotPaid(int $entrepriseId): array{
        try {
        	$bdd = Database::getInstance();
        	    $sql = "SELECT a.id,f.id AS form_id,f.titre,f.titre_url AS formTitreUrl,f.illustration,f.prix,f.date_debut,f.date_fin,u1.
                        nom AS auteur_nom,u1.prenoms AS auteur_prenoms,u2.nom AS participant_nom,u2.prenoms AS participant_prenoms,
                        u2.email AS participant_email,e.nom AS entreprise_nom,d.titre_url AS domaineTitreUrl FROM "
        	        .Constants::TABLE_ACHAT." AS a INNER JOIN ".Constants::TABLE_FORMATION." AS f INNER JOIN " .Constants::TABLE_UTILISATEUR." AS u1 INNER JOIN "
        	            .Constants::TABLE_UTILISATEUR." AS u2 INNER JOIN ".Constants::TABLE_ENTREPRISE." AS e INNER JOIN ".
        	            Constants::TABLE_DOMAINE." AS d ON a.formation_id=f.id AND f.auteur_id=u1.id AND a.utilisateur_id=u2.id AND
                        a.entreprise_id=e.id AND f.domaine_id=d.id WHERE a.entreprise_id=? AND a.is_paid=false AND f.date_debut > NOW()";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $entrepriseId);
        	$prepare->execute();
        	
        	$listeFormationNotBuy = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $achat = new Achat();
        	    $formation = new Formation();
        	    $domaine = new Domaine();
        	    $auteur = new Utilisateur();
        	    $utilisateur = new Utilisateur();
        	    $entreprise = new Entreprise();
        	    
        	    $auteur->setNom($result["auteur_nom"]);
        	    $auteur->setPrenoms($result["auteur_prenoms"]);
        	    
        	    $utilisateur->setNom($result["participant_nom"]);
        	    $utilisateur->setPrenoms($result["participant_prenoms"]);
        	    $utilisateur->setEmail($result["participant_email"]);

        	    $entreprise->setNom($result["entreprise_nom"]);
        	    
        	    $domaine->setTitreUrl($result["domaineTitreUrl"]);
        	    
        	    $formation->setId($result["form_id"]);
        	    $formation->setTitre($result["titre"]);
        	    $formation->setTitreUrl($result["formTitreUrl"]);
        	    $formation->setIllustration($result["illustration"]);
        	    $formation->setPrix($result["prix"]);
        	    $formation->setDateDebut($result["date_debut"]);
        	    $formation->setDateFin($result["date_fin"]);
        	    $formation->setAuteur($auteur);
        	    $formation->setDomaine($domaine);
        	    
        	    $achat->setId($result["id"]);
        	    $achat->setUtilisateur($utilisateur);
        	    $achat->setEntreprise($entreprise);
        	    $achat->setFormation($formation);
        	    
        	    $listeFormationNotBuy[] = $achat;
        	    
        	}
            
        	return $listeFormationNotBuy;
        } catch (Exception $e) {
        	die("Une erreur est survenue : " . $e -> getMessage());
        } finally{
            $prepare -> closeCursor();
        }
    }

    /**
     * Liste des formations présentes dans le panier et n'ayant pas encore été payé par un utilisateur
     *
     * @param int $utilisateurId
     * @return array
     */
    public function getUserListeFormationNotPaid(int $utilisateurId): array{
        try{
            $bdd = Database::getInstance();
            $sql = "SELECT a.id,f.id AS form_id,f.titre,f.titre_url AS formTitreUrl,f.illustration,f.prix,f.date_debut,f.date_fin,u.nom
                        AS auteur_nom,u.prenoms AS auteur_prenoms,d.titre_url AS domaineTitreUrl FROM "
                . Constants::TABLE_ACHAT . " AS a INNER JOIN " . Constants::TABLE_FORMATION . " AS f INNER JOIN "
                    . Constants::TABLE_UTILISATEUR . " AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.formation_id=f.id
                    AND f.auteur_id=u.id AND f.domaine_id=d.id WHERE a.utilisateur_id=? AND a.entreprise_id<=>null AND 
                    a.is_paid=false AND f.date_debut > NOW()";
                    
                    $prepare = Functions::bindPrepare($bdd, $sql, $utilisateurId);
                    $prepare->execute();
                    
                    $listeFormationNotBuy = array();
                    
                    while ($result = $prepare->fetch()) {
                        $achat = new Achat();
                        $formation = new Formation();
                        $domaine = new Domaine();
                        $auteur = new Utilisateur();
                        
                        $auteur->setNom($result["auteur_nom"]);
                        $auteur->setPrenoms($result["auteur_prenoms"]);
                        
                        $domaine->setTitreUrl($result["domaineTitreUrl"]);
                        
                        $formation->setId($result["form_id"]);
                        $formation->setTitre($result["titre"]);
                        $formation->setTitreUrl($result["formTitreUrl"]);
                        $formation->setIllustration($result["illustration"]);
                        $formation->setPrix($result["prix"]);
                        $formation->setDateDebut($result["date_debut"]);
                        $formation->setDateFin($result["date_fin"]);
                        $formation->setAuteur($auteur);
                        $formation->setDomaine($domaine);
                        
                        $achat->setId($result["id"]);
                        $achat->setFormation($formation);
                        
                        $listeFormationNotBuy[] = $achat;
                        
                    }
                    
                    return $listeFormationNotBuy;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Liste des formations d'une entreprise n'ayant pas encore confirmé le paiement (pour la page index de la partie entreprise)
     * @param int $entrepriseId
     * @return array
     */
    public function getListeFormationEntrepriseNotConfirmPaid(int $entrepriseId): array{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT f.titre,f.prix,f.date_debut,f.date_fin,u1.nom AS auteur_nom,u1.prenoms AS auteur_prenoms,
                        u2.nom AS participant_nom,u2.prenoms AS participant_prenoms,u2.email AS participant_email,e.nom AS entreprise_nom
                        ,d.titre AS domaineTitre FROM ".Constants::TABLE_ACHAT." AS a INNER JOIN ".Constants::TABLE_FORMATION
                       ." AS f INNER JOIN " .Constants::TABLE_UTILISATEUR." AS u1 INNER JOIN ".Constants::TABLE_UTILISATEUR
                       ." AS u2 INNER JOIN ".Constants::TABLE_ENTREPRISE." AS e INNER JOIN ". Constants::TABLE_DOMAINE
                       ." AS d ON a.formation_id=f.id AND f.auteur_id=u1.id AND a.utilisateur_id=u2.id AND
                        a.entreprise_id=e.id AND f.domaine_id=d.id WHERE a.entreprise_id=? AND a.is_paid=true 
                        AND a.confirm_paid=false AND f.date_debut > NOW()";
                    
                    $prepare = Functions::bindPrepare($bdd, $sql, $entrepriseId);
                    $prepare->execute();
                    
                    $listeFormationNotBuy = array();
                    
                    while ($result = $prepare->fetch()) {
                        $achat = new Achat();
                        $formation = new Formation();
                        $domaine = new Domaine();
                        $auteur = new Utilisateur();
                        $utilisateur = new Utilisateur();
                        $entreprise = new Entreprise();
                        
                        $auteur->setNom($result["auteur_nom"]);
                        $auteur->setPrenoms($result["auteur_prenoms"]);
                        
                        $utilisateur->setNom($result["participant_nom"]);
                        $utilisateur->setPrenoms($result["participant_prenoms"]);
                        $utilisateur->setEmail($result["participant_email"]);
                        
                        $entreprise->setNom($result["entreprise_nom"]);
                        
                        $domaine->setTitre($result["domaineTitre"]);
                        
                        $formation->setTitre($result["titre"]);
                        $formation->setPrix($result["prix"]);
                        $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                        $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                        $formation->setAuteur($auteur);
                        $formation->setDomaine($domaine);
                        
                        $achat->setUtilisateur($utilisateur);
                        $achat->setEntreprise($entreprise);
                        $achat->setFormation($formation);
                        
                        $listeFormationNotBuy[] = $achat;
                        
                    }
                    
                    return $listeFormationNotBuy;
        } catch (Exception $e) {
            die("Une erreur est survenue : " . $e -> getMessage());
        } finally{
            $prepare -> closeCursor();
        }
    }
    
    /**
     * Liste des utilisateurs d'une formation dans le panier
     * @param int $formationId
     * @param int $entrepriseId
     * @return array
     */
    public function getListeFormationParticipant(int $formationId, int $entrepriseId): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT a.id,a.paid_forced,u.nom,u.prenoms,u.email,u.telephone,u.fonction,a.confirm_paid FROM ".Constants::TABLE_ACHAT
        	           ." AS a INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u ON a.utilisateur_id=u.id WHERE a.formation_id=? AND 
                        a.entreprise_id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $formationId,$entrepriseId);
        	$prepare->execute();
        	
        	$listeUtilisateur = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $achat = new Achat();
        	    $utilisateur = new Utilisateur();
        	    
        	    $utilisateur->setNom($result["nom"]);
        	    $utilisateur->setPrenoms($result["prenoms"]);
        	    $utilisateur->setEmail($result["email"]);
        	    $utilisateur->setTelephone($result["telephone"]);
        	    $utilisateur->setFonction($result["fonction"]);
        	    
        	    $achat->setId($result["id"]);
        	    $achat->setConfirmPaid($result["confirm_paid"]);
        	    $achat->setPaidForced($result['paid_forced']);
        	    $achat->setUtilisateur($utilisateur);
        	    
        	    $listeUtilisateur[] = $achat;
        	}
            
        	return $listeUtilisateur;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Nombre de formations dans le panier n'ayant pas été payé par une entreprise
     * @param int $entrepriseOrUserId
     * @param bool $isEntreprise
     * @return int
     */
    public function nombreFormationNotPaidPanier(int $entrepriseOrUserId, bool $isEntreprise): int{
        try {
        	$bdd = Database::getInstance();
        	if($isEntreprise){
        	    $sql = "SELECT COUNT(id) AS nombre_formation FROM ".Constants::TABLE_ACHAT
        	    ." WHERE entreprise_id=? AND is_paid=false";
        	}else{
        	    $sql = "SELECT COUNT(id) AS nombre_formation FROM ".Constants::TABLE_ACHAT
        	    ." WHERE utilisateur_id=? AND entreprise_id<=>null AND is_paid=false";
        	}
        	
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $entrepriseOrUserId);
        	$prepare->execute();
        	
        	
        	$row = $prepare->fetch();
        	
        	return $row["nombre_formation"];
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Supprimer la formation du panier
     * @param int $id
     * @return bool
     */
    public function deleteAchat(int $id): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "DELETE FROM ".Constants::TABLE_ACHAT." WHERE id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $id);
            
        	return $prepare->execute();
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Cette fonction est utilisée lorsque l'utilisateur ou l'entreprise confirme le panier
     * @param int $entrepriseOrUserId
     * @param string $dateInscription
     * @param bool $isEntreprise
     * @return bool
     */
    public function updatePaid(int $entrepriseOrUserId, string $dateInscription, bool $isEntreprise, int $factureId): bool{
        try {
        	$bdd = Database::getInstance();
        	if($isEntreprise){
        	    $sql = "UPDATE ".Constants::TABLE_ACHAT." SET is_paid=true,date_inscription=?,facture_id=? WHERE entreprise_id=? AND is_paid=false";
        	}else{
        	    $sql = "UPDATE ".Constants::TABLE_ACHAT." SET is_paid=true,date_inscription=?,facture_id=? WHERE utilisateur_id=? AND entreprise_id<=>null 
                            AND is_paid=false";
        	}
        	
        	$prepare = Functions::bindPrepare($bdd, $sql,$dateInscription,$factureId, $entrepriseOrUserId);
            return $prepare->execute();
            
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Cette fonction est utilisée lorsque l'utilisateur envoie l'argent et que l'administrateur confirme ce paiement
     * pour un utilisateur particulier
     * @param int $factureId
     * @return bool
     */
    public function updateConfirmParticulierPaid(int $factureId): bool{
        try {
            $bdd = Database::getInstance();

             $sql = "UPDATE ".Constants::TABLE_ACHAT." SET confirm_paid=true WHERE facture_id=?";
            
            
            $prepare = Functions::bindPrepare($bdd, $sql, $factureId);
            return $prepare->execute();
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * Obtenir la liste des utilisateurs enrégistrés sous une entreprise étant n'etant pas encore enregistré sur la formation
     * @param int $entrepriseId
     * @param int $formationId
     * @return array
     */
    public function getListeExistParticipant(int $entrepriseId,int $formationId): array{
        try {
        	$bdd = Database::getInstance();
        	
        	$sql = "SELECT u.id,u.prenoms,u.nom,u.email FROM ".Constants::TABLE_UTILISATEUR." AS u LEFT JOIN ".
        	Constants::TABLE_ACHAT." AS a ON a.utilisateur_id = u.id WHERE u.entreprise_id=? AND (a.formation_id!=? 
            OR a.formation_id IS NULL) ORDER BY u.nom";
        	
        	/*$sql = "SELECT u.id,u.prenoms,u.nom,u.email FROM ".Constants::TABLE_UTILISATEUR." AS u LEFT JOIN ".
            	    Constants::TABLE_ACHAT." AS a ON a.utilisateur_id=u.id WHERE u.entreprise_id=? AND a.entreprise_id IS NULL
                OR a.formation_id!=? ORDER BY u.nom";*/
        	
                    
        	//$prepare = Functions::bindPrepare($bdd, $sql,$entrepriseId,$formationId);
        	$prepare = Functions::bindPrepare($bdd, $sql,$entrepriseId,$formationId);
        	$prepare->execute();
        	
        	$listeUtilisateur = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $utilisateur = new Utilisateur();
        	    
        	    $utilisateur->setId($result["id"]);
        	    $utilisateur->setNom($result["nom"]);
        	    $utilisateur->setPrenoms($result["prenoms"]);
        	    $utilisateur->setEmail($result["email"]);
        	    
        	    $listeUtilisateur[] = $utilisateur;
        	}
            
        	return $listeUtilisateur;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Liste des utilisateurs d'une formation dans le panier
     * @param int $formationId
     * @return array
     */
    public function getAllFormationParticipant(int $formationId): array{
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT u.nom,u.prenoms,u.email,u.telephone,u.fonction,a.is_paid FROM ".Constants::TABLE_ACHAT
            ." AS a INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u ON a.utilisateur_id=u.id WHERE a.formation_id=?";
            
            $prepare = Functions::bindPrepare($bdd, $sql, $formationId);
            $prepare->execute();
            
            $listeUtilisateur = array();
            
            while ($result = $prepare->fetch()) {
                $achat = new Achat();
                $utilisateur = new Utilisateur();
                
                $utilisateur->setNom($result["nom"]);
                $utilisateur->setPrenoms($result["prenoms"]);
                $utilisateur->setEmail($result["email"]);
                $utilisateur->setTelephone($result["telephone"]);
                $utilisateur->setFonction($result["fonction"]);
                
                $achat->setPaid($result["is_paid"]);
                $achat->setUtilisateur($utilisateur);
                
                $listeUtilisateur[] = $achat;
            }
            
            return $listeUtilisateur;
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }
    
    /**
     * Obetenir tous les participants à une formation d'une entreprise
     * @param int $entrepriseId
     * @param int $formationId
     * @return array
     */
   /* public function getAllEntrepriseFormationParticipant(?int $entrepriseId, ?int $formationId): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT u.id,u.prenoms,u.nom,u.email FROM ".Constants::TABLE_UTILISATEUR." AS u LEFT JOIN ".
            	Constants::TABLE_ACHAT." AS a ON a.utilisateur_id=u.id WHERE u.entreprise_id=? AND a.entreprise_id IS NULL
                OR a.formation_id=? ORDER BY u.nom";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, );
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }*/
    
    /**
     * Verifie s'il y a des achats
     * @return bool
     */
    public function isNotConfirmPaid(): bool{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id FROM ".Constants::TABLE_ACHAT." WHERE is_paid=true AND confirm_paid=false";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	if(!empty($prepare->fetch())){
        	    return true;
        	}
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
        
        return false;
    }
    
    /**
     * Les achats des particuliers qui n'ont pas encore été confirmé
     * @return array
     */
    /*public function getListeAchatParticulierNotComfirmPaid(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT a.id AS achatId,a.confirm_paid,a.date_inscription, a.paid_forced,f.id AS formId,f.titre,f.titre_url,f.date_debut,f.date_fin,f.lieu,f.prix,u.nom,
                        u.prenoms,u.email,u.telephone,d.titre_url AS domaineUrl FROM " .Constants::TABLE_ACHAT." AS a INNER JOIN "
                            .Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR
                            ." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.utilisateur_id=u.id AND a.formation_id=f.id 
                            AND f.domaine_id=d.id WHERE a.entreprise_id IS NULL AND a.is_paid=true AND a.confirm_paid=false";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeAchat = array();
        	
        	while($result = $prepare->fetch()){
        	    $achat = new Achat();
        	    $utilisateur = new Utilisateur();
        	    $formation = new Formation();
        	    $domaine = new Domaine();
        	    
        	    $utilisateur->setNom($result["nom"]);
        	    $utilisateur->setPrenoms($result["prenoms"]);
        	    $utilisateur->setEmail($result["email"]);
        	    $utilisateur->setTelephone($result["telephone"]);
        	    
        	    $domaine->setTitreUrl($result["domaineUrl"]);
        	    
        	    $formation->setId($result["formId"]);
        	    $formation->setTitre($result["titre"]);
        	    $formation->setTitreUrl($result["titre_url"]);
        	    $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
        	    $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
        	    $formation->setPrix($result["prix"]);
        	    $formation->setLieu($result["lieu"]);
        	    $formation->setDomaine($domaine);
        	    
        	    $achat->setId($result["achatId"]);
        	    $achat->setConfirmPaid($result["confirm_paid"]);
        	    $achat->setUtilisateur($utilisateur);
        	    $achat->setFormation($formation);
        	    $achat->setPaidForced($result["paid_forced"]);
        	    $achat->setDateInscription(Functions::convertDateEnToFr($result["date_inscription"]));
        	    
        	    $listeAchat[] = $achat;
        	    
        	}
        	
            return $listeAchat;	
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }*/
    
    /**
     * Les achats d'entreprise pas encore confirmés
     * @return array
     */
    /*public function getListeAchatEntrepriseNotConfirmPaid(): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT a.id AS achatId,a.confirm_paid,a.date_inscription,a.paid_forced,f.id AS formId,f.titre,f.titre_url,f.date_debut,f.date_fin,f.prix,
                        f.lieu,u.nom,u.prenoms,u.email,u.telephone,u2.email AS pEmail,u2.telephone AS pTel,e.id AS entrepId,
                        e.nom AS entrepNom,d.titre_url AS domaineUrl FROM " .Constants::TABLE_ACHAT." AS a INNER JOIN "
                       .Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR." AS u INNER JOIN "
                       .Constants::TABLE_ENTREPRISE." AS e INNER JOIN ".Constants::TABLE_UTILISATEUR
                       ." AS u2 INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.utilisateur_id=u.id AND a.formation_id=f.id 
                        AND a.entreprise_id=e.id AND e.utilisateur_id=u2.id AND f.domaine_id=d.id WHERE 
                        a.entreprise_id IS NOT NULL AND a.is_paid=true AND a.confirm_paid=false";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql);
        	$prepare->execute();
        	
        	$listeAchat = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $achat = new Achat();
        	    $utilisateur = new Utilisateur();
        	    $formation = new Formation();
        	    $domaine = new Domaine();
        	    $entreprise = new Entreprise();
        	    $entrepriseUtilisateur = new Utilisateur();
        	    
        	    $utilisateur->setNom($result["nom"]);
        	    $utilisateur->setPrenoms($result["prenoms"]);
        	    $utilisateur->setEmail($result["email"]);
        	    $utilisateur->setTelephone($result["telephone"]);
        	    
        	    $domaine->setTitreUrl($result["domaineUrl"]);
        	    
        	    $formation->setId($result["formId"]);
        	    $formation->setTitre($result["titre"]);
        	    $formation->setTitreUrl($result["titre_url"]);
        	    $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
        	    $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
        	    $formation->setPrix($result["prix"]);
        	    $formation->setLieu($result["lieu"]);
        	    $formation->setDomaine($domaine);
        	    
        	    $entrepriseUtilisateur->setEmail($result["pEmail"]);
        	    $entrepriseUtilisateur->setTelephone($result["pTel"]);
        	    
        	    $entreprise->setId($result["entrepId"]);
        	    $entreprise->setNom($result["entrepNom"]);
        	    $entreprise->setUtilisateur($entrepriseUtilisateur);
        	    
        	    $achat->setId($result["achatId"]);
        	    $achat->setConfirmPaid($result["confirm_paid"]);
        	    $achat->setUtilisateur($utilisateur);
        	    $achat->setFormation($formation);
        	    $achat ->setEntreprise($entreprise);
        	    $achat->setPaidForced($result["paid_forced"]);
        	    $achat->setDateInscription(Functions::convertDateEnToFr($result["date_inscription"]));
        	    
        	    $listeAchat[] = $achat;
        	}
            
        	return $listeAchat;
        	
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }*/

    /**
     * Obtenir la liste des formations achetées par un utilisateur
     * @param int $id
     * @param bool $isEntreprise
     * @return array
     */
    public function getListeFormationConfirmPaid(int $id,bool $isEntreprise): array{
        try {
            $bdd = Database::getInstance();
            
            if($isEntreprise){
                $sql = "SELECT d.id AS achatId,f.id AS formationId,f.titre,f.titre_url,f.illustration,f.date_debut,f.date_fin,d.titre_url AS domaineUrl,d.titre AS domaineTitre,u.nom,u.prenoms FROM ".Constants::TABLE_ACHAT
                ." AS a INNER JOIN ".Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR
                ." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.formation_id=f.id AND f.auteur_id=u.id AND
                   f.domaine_id=d.id WHERE a.entreprise_id =? AND a.is_paid=true AND a.confirm_paid=true";
            }else{
                $sql = "SELECT d.id AS achatId,f.id AS formationId,f.titre,f.titre_url,f.illustration,f.date_debut,f.date_fin,d.titre_url AS domaineUrl,d.titre AS domaineTitre,u.nom,u.prenoms FROM ".Constants::TABLE_ACHAT
                ." AS a INNER JOIN ".Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR
                ." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.formation_id=f.id AND f.auteur_id=u.id AND
               f.domaine_id=d.id WHERE a.entreprise_id<=>null AND a.utilisateur_id=? AND a.is_paid=true AND 
                a.confirm_paid=true";
            }         
            
            
            $prepare = Functions::bindPrepare($bdd, $sql, $id);
            $prepare->execute();
            
            $listeAchat = array();
            
            while ($result = $prepare->fetch()) {
                $achat = new Achat();
                $formation = new Formation();
                $domaine = new Domaine();
                $auteur = new Utilisateur();

                $domaine->setTitre($result["titre"]);
                $domaine->setTitreUrl($result["domaineUrl"]);
                
                $auteur->setNom($result["nom"]);
                $auteur->setPrenoms($result["prenoms"]);

                $formation->setId($result["formationId"]);
                $formation->setTitreUrl($result["titre_url"]);
                $formation->setTitre($result["titre"]);
                $formation->setIllustration($result["illustration"]);
                $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                $formation->setAuteur($auteur);
                $formation->setDomaine($domaine);

                $achat->setId($result["achatId"]);
                $achat->setFormation($formation);
                
                $listeAchat[] = $achat;
            }
            
            return $listeAchat;
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * Obtenir la liste des formations commande par un utilisateur, mais donc l'achat n'a pas encore été confirmé
     * @param int $id
     * @param bool $isEntreprise
     * @return array
     */
    public function getListeFormationNotConfirmPaid(int $id, bool $isEntreprise): array{
        try {
            $bdd = Database::getInstance();
            
            if($isEntreprise){
                $sql = "SELECT a.id AS achatId,a.paid_forced,f.id,f.titre,f.titre_url,f.illustration,f.date_debut,f.date_fin,f.prix,d.titre_url AS domaineUrl,d.titre AS domaineTitre,u.nom,u.prenoms FROM ".Constants::TABLE_ACHAT
                ." AS a INNER JOIN ".Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR
                ." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.formation_id=f.id AND f.auteur_id=u.id AND 
                  f.domaine_id=d.id WHERE a.entreprise_id =? AND a.is_paid=true AND a.confirm_paid=false GROUP BY f.titre";
            }else{
                $sql = "SELECT a.id AS achatId,a.paid_forced,f.id,f.titre,f.titre_url,f.illustration,f.date_debut,f.date_fin,f.prix,d.titre_url AS domaineUrl,d.titre AS domaineTitre,u.nom,u.prenoms FROM "
                    .Constants::TABLE_ACHAT
                ." AS a INNER JOIN ".Constants::TABLE_FORMATION." AS f INNER JOIN ".Constants::TABLE_UTILISATEUR
                ." AS u INNER JOIN ".Constants::TABLE_DOMAINE." AS d ON a.formation_id=f.id AND f.auteur_id=u.id AND 
                f.domaine_id=d.id WHERE a.entreprise_id<=>null AND a.utilisateur_id=? AND a.is_paid=true AND a.confirm_paid=false";
            }
            
            $prepare = Functions::bindPrepare($bdd, $sql, $id);
            $prepare->execute();
            
            $listeAchat = array();
            
            while ($result = $prepare->fetch()) {
                $achat = new Achat();
                $formation = new Formation();
                $domaine = new Domaine();
                $auteur = new Utilisateur();
                
                $auteur->setNom($result["nom"]);
                $auteur->setPrenoms($result["prenoms"]);
                
                $domaine->setTitre(ucfirst(strtolower($result["domaineTitre"])));
                $domaine->setTitreUrl($result["domaineUrl"]);
                
                $formation->setId($result["id"]);
                $formation->setDateDebut(Functions::convertDateEnToFr($result["date_debut"]));
                $formation->setDateFin(Functions::convertDateEnToFr($result["date_fin"]));
                $formation->setPrix($result["prix"]);
                $formation->setTitreUrl($result["titre_url"]);
                $formation->setTitre($result["titre"]);
                $formation->setIllustration($result["illustration"]);
                $formation->setAuteur($auteur);
                $formation->setDomaine($domaine);
                
                $achat->setId($result["achatId"]);
                $achat->setFormation($formation);
                $achat->setPaidForced($result["paid_forced"]);
                
                $listeAchat[] = $achat;
            }
            
            return $listeAchat;
            
        } catch (Exception $e) {
            die("Une erreur est survenue : ".$e->getMessage());
        }finally{
            $prepare->closeCursor();
        }
    }

    /**
     * Verifier que l'utilisateur n'est pas deja enregistré à la formation
     * @param int $utilisateurId
     * @param int $formationId
     * @return Achat|null
     */
    public function userIsRegisterInFormationAndPaidOrConfirmPaid(int $utilisateurId, int $formationId): ?Achat{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT id,is_paid,confirm_paid FROM ".Constants::TABLE_ACHAT." WHERE utilisateur_id=? AND formation_id=?";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $utilisateurId,$formationId);
        	$prepare->execute();

            $achat = null;
            while ($result = $prepare->fetch()){
                $achat = new Achat();

                $achat->setPaid($result["is_paid"]);
                $achat->setConfirmPaid($result["confirm_paid"]);
            }
            return $achat;
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }
    
    /**
     * Liste des personnes inscrites à une formation
     * @param int $formationId
     * @return array
     */
    public function getListeFormationAcheteur(int $formationId): array{
        try {
        	$bdd = Database::getInstance();
        	$sql = "SELECT u.nom,u.prenoms,u.email,e.nom AS entrepNom FROM ".Constants::TABLE_ACHAT." AS a INNER JOIN "
        	    .Constants::TABLE_UTILISATEUR." AS u ON a.utilisateur_id=u.id INNER JOIN ".Constants::TABLE_FORMATION
        	   ." AS f ON a.formation_id=f.id LEFT JOIN ".Constants::TABLE_ENTREPRISE." AS e ON a.entreprise_id=e.id 
                WHERE f.id=? AND a.confirm_paid=true";
                    
        	$prepare = Functions::bindPrepare($bdd, $sql, $formationId);
        	$prepare->execute();
        	
        	$listeAchteur = array();
        	
        	while ($result = $prepare->fetch()) {
        	    $achat = new Achat();
        	    $utilisateur = new Utilisateur();
        	    $entreprise = new Entreprise();
        	    
        	    $utilisateur->setNom($result["nom"]);
        	    $utilisateur->setPrenoms($result["prenoms"]);
        	    $utilisateur->setEmail($result["email"]);
        	    
        	    $entreprise->setNom($result["entrepNom"]);
        	    
        	    $achat->setUtilisateur($utilisateur);
        	    $achat->setEntreprise($entreprise);
        	    
        	    $listeAchteur[] = $achat;
        	}
            
        	return $listeAchteur;
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /**
     * Mettre à jour le paiement obligatoire
     * @param int $id
     * @param bool $paidForced
     * @return bool
     */
    public function updatePaidForced(int $id, bool $paidForced): bool{
        try {
        	$bdd = Database::getInstance();
        	
        	if($paidForced){
        	    $sql = "UPDATE ".Constants::TABLE_ACHAT." SET paid_forced=true WHERE facture_id=?";
        	}else{
        	    $sql = "UPDATE ".Constants::TABLE_ACHAT." SET paid_forced=false WHERE facture_id=?";
        	}
            
        	$prepare = Functions::bindPrepare($bdd, $sql, $id);
        	return $prepare->execute();
        
        } catch (Exception $e) {
        	die("Une erreur est survenue : ".$e->getMessage());
        }finally{
        	$prepare->closeCursor();
        }
    }

    /** Savoir si la formation a été payé ou pas
     * @param int $formationId
     * @param $utilisateurId
     * @return void
     */
    /*public function getIsPaidAndConfirmPaid(int $formationId, $utilisateurId) : ?Achat{
        try {

            $bdd = Database::getInstance ();
            $sql = "SELECT is_paid, confirm_paid FROM ".Constants::TABLE_ACHAT." WHERE utilisateur_id=? AND formation_id=?";

            $prepare = Functions::bindPrepare ($bdd, $sql,$formationId,$utilisateurId);
            $prepare->execute();
            $achat = null;
            while ($result = $prepare->fetch()){
                $achat = new Achat();

                $achat->setPaid($result["is_paid"]);
                $achat->setConfirmPaid($result["confirm_paid"]);
            }
            return $achat;
        } catch ( Exception $e ) {
            die ( "Une erreur est survenue -> " . $e->getMessage () );
        } finally {
            $prepare->closeCursor ();
        }
    }*/

}

