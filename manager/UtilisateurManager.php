<?php

namespace manager;

require_once($_SERVER ["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Pays.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/NombreEmploye.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Objectif.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Secteur.php");
require_once($_SERVER ["DOCUMENT_ROOT"] . "/utils/Constants.php");
require_once($_SERVER ["DOCUMENT_ROOT"] . "/manager/Database.php");

use Exception;
use src\Utilisateur;
use utils\Constants;
use utils\Functions;
use src\TypeCompte;
use src\Pays;
use src\Entreprise;
use src\NombreEmploye;
use src\Objectif;
use src\Secteur;


class UtilisateurManager
{

    /**
     * Enregistrer un utilisateur en base de données
     *
     * @param Utilisateur $utilisateur
     * @param bool $isActive
     * @return int
     */
    public function inscription(Utilisateur $utilisateur, bool $isActive): int
    {
        try {
            $bdd = Database::getInstance();

            if ($isActive) {
                $sql = "INSERT INTO " . Constants::TABLE_UTILISATEUR . "(nom,prenoms,email,password,telephone,token,active
                ,type_compte_id,date_inscription,entreprise_id) VALUES(?,?,?,?,?,?,?,?,?,?)";

                $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->getNom(), $utilisateur->getPrenoms(), $utilisateur->getEmail(), $utilisateur->getPassword(), $utilisateur->getTelephone(),
                    $utilisateur->getToken(), $utilisateur->isActive(), $utilisateur->getTypeCompte()->getId(), $utilisateur->getDateInscription(), $utilisateur->getEntreprise()->getId());
            } else {
                $sql = "INSERT INTO " . Constants::TABLE_UTILISATEUR . "(nom,prenoms,email,password,telephone,token
                ,type_compte_id,date_inscription,entreprise_id) VALUES(?,?,?,?,?,?,?,?,?)";

                $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->getNom(), $utilisateur->getPrenoms(), $utilisateur->getEmail(), $utilisateur->getPassword(), $utilisateur->getTelephone(),
                    $utilisateur->getToken(), $utilisateur->getTypeCompte()->getId(), $utilisateur->getDateInscription(), $utilisateur->getEntreprise()->getId());
            }

            $prepare->execute();
            return $bdd->lastInsertId();

        } catch(Exception $e) {

            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Verifie si une adresse email existe en base de données
     *
     * @param string $email
     * @return bool
     */
    public function emailExist(string $email): bool
    {
        try {

            $bdd = Database::getInstance();
            $sql = "SELECT id FROM " . Constants::TABLE_UTILISATEUR . " WHERE email=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $email);
            $prepare->execute();
            $result = $prepare->fetch();

            if (empty ($result)) {
                return false;
            }

            return true;
        } catch(Exception $e) {

            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Verifie si une adresse email existe en base de données et recupérer l'utilisateur
     *
     * @param string $email
     * @return Utilisateur|null
     */
    public function userEmailExist(string $email): ?Utilisateur
    {
        try {

            $bdd = Database::getInstance();
            $sql = "SELECT id,prenoms FROM " . Constants::TABLE_UTILISATEUR . " WHERE email=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $email);
            $prepare->execute();

            $utilisateur = null;

            while ($result = $prepare->fetch()) {
                $utilisateur = new Utilisateur();

                $utilisateur->setId($result["id"]);
                $utilisateur->setPrenoms($result["prenoms"]);

            }

            return $utilisateur;
        } catch(Exception $e) {

            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Mettre le token de l'utilisateur a null et activer le compte
     *
     * @param int $id
     * @return bool
     */
    public function updateTokenNullAndActiveCompte(int $id): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET token=?,active=? WHERE id=?";
            $prepare = Functions::bindPrepare($bdd, $sql, null, true, $id);

            return $prepare->execute();
        } catch(Exception $e) {
            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Verifie l'adresse Email et le mot de passe pour connecter l'utilisateur ou le Token
     * @param string|null $emailOrToken
     * @param bool $isToken
     * @return Utilisateur|null
     */
    public function connexion(?string $emailOrToken, bool $isToken): ?Utilisateur
    {
        try {
            $bdd = Database::getInstance();

            if ($isToken) {

                $sql = "SELECT u.id,u.nom,u.prenoms,u.password,u.email,u.telephone,u.date_naissance,u.date_inscription,u.fonction,u.pays_id,
                        u.profil_picture,u.bloquer,u.derniere_connexion,t.id AS type_compte_id,t.nom AS type_compte_nom,e.id AS entreprise_id,e.nom AS entreprise_nom,e.nombre_employe_id,
                        e.objectif_id,e.secteur_id FROM " . Constants::TABLE_UTILISATEUR . " AS u INNER JOIN " . Constants::TABLE_TYPE_COMPTE . " AS t ON u.type_compte_id = t.id LEFT JOIN " . Constants::TABLE_ENTREPRISE . " AS e ON u.entreprise_id=e.id WHERE token=?";

            } else {
                $sql = "SELECT u.id,u.nom,u.prenoms,u.password,u.email,u.telephone,u.date_naissance,u.date_inscription,u.fonction,u.pays_id,
                        u.profil_picture,u.bloquer,u.derniere_connexion,t.id AS type_compte_id,t.nom AS type_compte_nom,e.id AS entreprise_id,e.nom AS entreprise_nom,e.nombre_employe_id,
                        e.objectif_id,e.secteur_id FROM " . Constants::TABLE_UTILISATEUR . " AS u INNER JOIN " . Constants::TABLE_TYPE_COMPTE . " AS t ON u.type_compte_id = t.id LEFT JOIN " . Constants::TABLE_ENTREPRISE . " AS e ON u.entreprise_id=e.id WHERE email=? AND active=true";
            }


            $prepare = Functions::bindPrepare($bdd, $sql, $emailOrToken);
            $prepare->execute();

            $utilisateur = null;

            while ($result = $prepare->fetch()) {
                $utilisateur = new Utilisateur ();
                $typeCompte = new TypeCompte();
                $pays = new Pays();
                $entreprise = new Entreprise();
                $nombreEmploye = new NombreEmploye();
                $objectif = new Objectif();
                $secteur = new Secteur();

                $typeCompte->setId($result["type_compte_id"]);
                $typeCompte->setNom($result["type_compte_nom"]);

                $pays->setId($result["pays_id"]);

                $nombreEmploye->setId($result["nombre_employe_id"]);
                $objectif->setId($result["objectif_id"]);
                $secteur->setId($result["secteur_id"]);

                $entreprise->setId($result["entreprise_id"]);
                $entreprise->setNom($result["entreprise_nom"]);
                $entreprise->setNombreEmploye($nombreEmploye);
                $entreprise->setObjectif($objectif);
                $entreprise->setSecteur($secteur);

                $utilisateur->setId($result ["id"]);
                $utilisateur->setNom($result ["nom"]);
                $utilisateur->setPrenoms($result ["prenoms"]);
                $utilisateur->setEmail($result ["email"]);
                $utilisateur->setPassword($result["password"]);
                $utilisateur->setTelephone($result ["telephone"]);
                $utilisateur->setBloquer($result["bloquer"]);
                $utilisateur->setDerniereConnexion(Functions::convertDateEnToFr($result["derniere_connexion"]));
                $utilisateur->setDateNaissance(Functions::convertDateEnToFr($result["date_naissance"]));
                $utilisateur->setDateInscription($result["date_inscription"]);
                $utilisateur->setFonction($result ["fonction"]);
                $utilisateur->setProfilPicture($result["profil_picture"]);
                $utilisateur->setTypeCompte($typeCompte);
                $utilisateur->setPays($pays);
                $utilisateur->setEntreprise($entreprise);
            }

            return $utilisateur;
        } catch(Exception $e) {
            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }


    /**
     * Verifie l'adresse Email et le mot de passe pour connecter l'utilisateur
     * @param string|null $email
     * @return Utilisateur|null
     */
    public function connexionAdmin(?string $email): ?Utilisateur
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT u.id,u.nom,u.prenoms,u.password,u.email,u.telephone,u.date_naissance,u.date_inscription,u.fonction,u.pays_id,
                        u.profil_picture,u.bloquer,u.derniere_connexion,t.id AS type_compte_id,t.nom AS type_compte_nom,e.id AS entreprise_id,e.nom AS entreprise_nom,e.nombre_employe_id,
                        e.objectif_id,e.secteur_id FROM " . Constants::TABLE_UTILISATEUR . " AS u INNER JOIN " . Constants::TABLE_TYPE_COMPTE . " AS t ON u.type_compte_id = t.id LEFT JOIN " . Constants::TABLE_ENTREPRISE . " AS e ON u.entreprise_id=e.id WHERE u.email=? AND u.type_compte_id=" . Constants::COMPTE_ADMIN;

            $prepare = Functions::bindPrepare($bdd, $sql, $email);
            $prepare->execute();

            $utilisateur = null;

            while ($result = $prepare->fetch()) {
                $utilisateur = new Utilisateur ();
                $typeCompte = new TypeCompte();
                $pays = new Pays();
                $entreprise = new Entreprise();
                $nombreEmploye = new NombreEmploye();
                $objectif = new Objectif();
                $secteur = new Secteur();

                $typeCompte->setId($result["type_compte_id"]);
                $typeCompte->setNom($result["type_compte_nom"]);

                $pays->setId($result["pays_id"]);

                $nombreEmploye->setId($result["nombre_employe_id"]);
                $objectif->setId($result["objectif_id"]);
                $secteur->setId($result["secteur_id"]);

                $entreprise->setId($result["entreprise_id"]);
                $entreprise->setNom($result["entreprise_nom"]);
                $entreprise->setNombreEmploye($nombreEmploye);
                $entreprise->setObjectif($objectif);
                $entreprise->setSecteur($secteur);

                $utilisateur->setId($result ["id"]);
                $utilisateur->setNom($result ["nom"]);
                $utilisateur->setPrenoms($result ["prenoms"]);
                $utilisateur->setEmail($result ["email"]);
                $utilisateur->setPassword($result["password"]);
                $utilisateur->setTelephone($result ["telephone"]);
                $utilisateur->setBloquer($result["bloquer"]);
                $utilisateur->setDerniereConnexion(Functions::convertDateEnToFr($result["derniere_connexion"]));
                $utilisateur->setDateNaissance(Functions::convertDateEnToFr($result["date_naissance"]));
                $utilisateur->setDateInscription($result["date_inscription"]);
                $utilisateur->setFonction($result ["fonction"]);
                $utilisateur->setProfilPicture($result["profil_picture"]);
                $utilisateur->setTypeCompte($typeCompte);
                $utilisateur->setPays($pays);
                $utilisateur->setEntreprise($entreprise);
            }

            return $utilisateur;
        } catch(Exception $e) {
            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }


    /**
     * Mettre à jour le champ connect
     * @param int $id
     * @param string $now
     */
    public function updateConnect(int $id, string $now): void
    {
        try {

            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET connect=?,derniere_connexion=? WHERE id=?";
            $prepare = Functions::bindPrepare($bdd, $sql, true, $now, $id);

            $prepare->execute();

        } catch(Exception $e) {
            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Deconnecter un utilisateur en BDD
     * @param int $id
     * @return bool
     */
    public function deconnexion(int $id): bool
    {
        try {

            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET connect=false WHERE id=?";
            $prepare = Functions::bindPrepare($bdd, $sql, $id);

            return $prepare->execute();

        } catch(Exception $e) {
            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Mise à jour des informations personnelles (nom, prenoms,etc)
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function updateInfosPersos(Utilisateur $utilisateur): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET nom=?, prenoms=?, telephone=?, date_naissance=?, pays_id=?, 
                            fonction=? WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->getNom(), $utilisateur->getPrenoms(), $utilisateur->getTelephone(), $utilisateur->getDateNaissance(), $utilisateur->getPays()->getId(), $utilisateur->getFonction(), $utilisateur->getId());

            return $prepare->execute();
        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Modification de l'adresse E-mail
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function updateEmail(Utilisateur $utilisateur): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET email=?,password=? WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->getEmail(), $utilisateur->getPassword(), $utilisateur->getId());

            return $prepare->execute();
        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Modifier le mot de passe
     * @param string $password
     * @param int $id
     * @return bool
     */
    public function updatePassword(string $password, int $id): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET password=? WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $password, $id);

            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Recuperer tous les administrateurs en BDD
     * @param int $typeCompte
     * @return array
     */
    public function getAllUser(int $typeCompte): array
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,nom,prenoms,email,date_inscription,connect,active,bloquer,date_blocage,
                        motif_blocage,derniere_connexion FROM " . Constants::TABLE_UTILISATEUR . " WHERE type_compte_id=" . $typeCompte;

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();

            $listeAdmin = array();

            while ($result = $prepare->fetch()) {
                $utilisateur = new Utilisateur();

                $utilisateur->setId($result["id"]);
                $utilisateur->setNom($result["nom"]);
                $utilisateur->setPrenoms($result["prenoms"]);
                $utilisateur->setEmail($result["email"]);
                $utilisateur->setDateInscription(Functions::convertDateEnToFr($result ["date_inscription"]));
                $utilisateur->setConnect($result["connect"]);
                $utilisateur->setActive($result["active"]);
                $utilisateur->setBloquer($result["bloquer"]);
                $utilisateur->setDateBlocage(Functions::convertDateEnToFr($result["date_blocage"]));
                $utilisateur->setMotifBlocage($result["motif_blocage"]);
                $utilisateur->setDerniereConnexion(Functions::convertDateEnToFr($result["derniere_connexion"]));

                $listeAdmin[] = $utilisateur;

            }

            return $listeAdmin;
        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Bloquer ou debloquer un utilisateur
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function bloquerAndDebloquer(Utilisateur $utilisateur): bool
    {
        try {
            $bdd = Database::getInstance();

            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET bloquer=?,motif_blocage=?,date_blocage=? WHERE id=?";
            $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->isBloquer(), $utilisateur->getMotifBlocage(), $utilisateur->getDateBlocage(), $utilisateur->getId());

            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Ajouter un participant en l'inscrivant
     * @param Utilisateur $utilisateur
     * @return int
     */
    public function inscriptionParticipant(Utilisateur $utilisateur): int
    {
        try {
            $bdd = Database::getInstance();
            $sql = "INSERT INTO " . Constants::TABLE_UTILISATEUR . "(nom,prenoms,email,password,telephone,active,fonction
                ,type_compte_id,date_inscription,entreprise_id) VALUES(?,?,?,?,?,?,?,?,?,?)";

            $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->getNom(), $utilisateur->getPrenoms(), $utilisateur->getEmail(), $utilisateur->getPassword(), $utilisateur->getTelephone(),
                $utilisateur->isActive(), $utilisateur->getFonction(), $utilisateur->getTypeCompte()->getId(), $utilisateur->getDateInscription(), $utilisateur->getEntreprise()->getId());

            $prepare->execute();

            return $bdd->lastInsertId();
        } catch(Exception $e) {

            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Changer la pp
     * @param Utilisateur $utilisateur
     * @return bool
     */
    public function updateProfilPicture(Utilisateur $utilisateur): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET profil_picture=? WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $utilisateur->getProfilPicture(), $utilisateur->getId());
            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Convertir l'utilisateur en admin et vice versa
     * @param int $id
     * @param int $typeCompte
     * @return bool
     */
    public function defineAndAnnulerAdmin(int $id, int $typeCompte): bool
    {
        try {
            $bdd = Database::getInstance();
            $sql = "UPDATE " . Constants::TABLE_UTILISATEUR . " SET type_compte_id=" . $typeCompte . " WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $id);
            return $prepare->execute();

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Recuperer l'utilisateur avec le'id
     *
     * @param int|null $id
     * @return Utilisateur|null
     */
    public function getUtilisateurById(?int $id): ?Utilisateur
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT prenoms,email FROM " . Constants::TABLE_UTILISATEUR . " WHERE id=?";

            $prepare = Functions::bindPrepare($bdd, $sql, $id);
            $prepare->execute();

            $utilisateur = null;

            while ($result = $prepare->fetch()) {
                $utilisateur = new Utilisateur ();
                $utilisateur->setPrenoms($result ["prenoms"]);
                $utilisateur->setEmail($result["email"]);

            }

            return $utilisateur;
        } catch(Exception $e) {
            die ("Une erreur est survenue -> " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Nombre total d'utilisateurs en BDD admin et users
     * @param bool $isAdmin
     * @return int
     */
    public function getNombreUser(bool $isAdmin): int
    {
        try {
            $bdd = Database::getInstance();
            if ($isAdmin) {
                $sql = "SELECT COUNT(id) AS nb_users FROM " . Constants::TABLE_UTILISATEUR . " WHERE type_compte_id=" . Constants::COMPTE_ADMIN;
            } else {
                $sql = "SELECT COUNT(id) AS nb_users FROM " . Constants::TABLE_UTILISATEUR . " WHERE type_compte_id=" . Constants::COMPTE_STANDARD;
            }

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();

            $row = $prepare->fetch();

            return $row["nb_users"];

        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

    /**
     * Recuperer tous les utilisateurs d'une entreprise
     * @param int|null $entrepriseId
     * @return array
     */
    public function getAllEntrepriseUser(?int $entrepriseId): array
    {
        try {
            $bdd = Database::getInstance();
            $sql = "SELECT id,nom,prenoms,email,date_inscription,connect,active,type_compte_id,bloquer,date_blocage,
                        motif_blocage,derniere_connexion FROM " . Constants::TABLE_UTILISATEUR . " WHERE entreprise_id=" . $entrepriseId;

            $prepare = Functions::bindPrepare($bdd, $sql);
            $prepare->execute();

            $listeAdmin = array();

            while ($result = $prepare->fetch()) {
                $utilisateur = new Utilisateur();
                $typeCompte = new TypeCompte();

                $typeCompte->setId($result["type_compte_id"]);

                $utilisateur->setId($result["id"]);
                $utilisateur->setNom($result["nom"]);
                $utilisateur->setPrenoms($result["prenoms"]);
                $utilisateur->setEmail($result["email"]);
                $utilisateur->setDateInscription(Functions::convertDateEnToFr($result ["date_inscription"]));
                $utilisateur->setConnect($result["connect"]);
                $utilisateur->setActive($result["active"]);
                $utilisateur->setBloquer($result["bloquer"]);
                $utilisateur->setDateBlocage(Functions::convertDateEnToFr($result["date_blocage"]));
                $utilisateur->setMotifBlocage($result["motif_blocage"]);
                $utilisateur->setDerniereConnexion(Functions::convertDateEnToFr($result["derniere_connexion"]));
                $utilisateur->setTypeCompte($typeCompte);

                $listeAdmin[] = $utilisateur;

            }
            return $listeAdmin;
        } catch(Exception $e) {
            die("Une erreur est survenue : " . $e->getMessage());
        } finally {
            $prepare->closeCursor();
        }
    }

}

