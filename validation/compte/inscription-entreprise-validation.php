<?php

namespace validation\compte;

require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/NombreEmploye.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Objectif.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/Secteur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/EntrepriseManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/NombreEmployeManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/ObjectifManager.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/manager/SecteurManager.php");

use Exception;
use utils\Constants;
use utils\Functions;
use manager\UtilisateurManager;
use manager\NombreEmployeManager;
use manager\ObjectifManager;
use manager\SecteurManager;
use src\Utilisateur;
use src\Entreprise;
use src\NombreEmploye;
use src\Objectif;
use manager\EntrepriseManager;
use DateTime;
use src\TypeCompte;
use src\Secteur;

$nom = Functions::getValueChamp($_POST["nom"]);
$prenoms = Functions::getValueChamp($_POST["prenoms"]);
$email = Functions::getValueChamp($_POST["email"]);
$telephone = Functions::getValueChamp($_POST["telephone"]);
$entrepriseNom = Functions::getValueChamp($_POST["entreprise"]);
$nombreEmployeId = Functions::getValueChamp($_POST["employes"]);
$objectifNameOrId = Functions::getValueChamp($_POST["objectif"]);
$secteurNameOrId = Functions::getValueChamp($_POST["secteur"]);

$erreurs = array();

$utilisateurManager = new UtilisateurManager();
$entrepriseManager = new EntrepriseManager();
$nombreEmployeManager = new NombreEmployeManager();
$objectifManager = new ObjectifManager();
$secteurManager = new SecteurManager();


try {
    Functions::validTexte($nom, "nom", 2, 15, true);
} catch(Exception $e) {
    $erreurs["nom"] = $e->getMessage();
}


try {
    Functions::validTexte($prenoms, "prenoms", 2, 30, true);
} catch(Exception $e) {
    $erreurs["prenoms"] = $e->getMessage();
}


try {
    Functions::validEmail($email, $utilisateurManager);
} catch(Exception $e) {
    $erreurs["email"] = $e->getMessage();
}


try {
    Functions::validTelephone($telephone);
} catch(Exception $e) {
    $erreurs["telephone"] = $e->getMessage();
}


try {
    Functions::validEntreprise($entrepriseNom, $entrepriseManager);
} catch(Exception $e) {
    $erreurs["entreprise"] = $e->getMessage();
}


if (!$nombreEmployeManager->isExist(intval($nombreEmployeId))) {
    $erreurs["employes"] = "Le nombre d'employés selectionné est incorrect.";
}

//Si la valeur numérique est égale à 0 alors il s'agit d'un nouvel objectif
if (intval($objectifNameOrId) == 0 && $objectifManager->isNomExist($objectifNameOrId)) {
    $erreurs["objectif"] = "L'objectif existe déjà.";
} else if (intval($objectifNameOrId) == 0 && !$objectifManager->isNomExist($objectifNameOrId)) {
    try {
        Functions::validTexte($objectifNameOrId, "objectif", 10, 50, true);
    } catch(Exception $e) {
        $erreurs["objectif"] = $e->getMessage();
    }
} else if (intval($objectifNameOrId) != 0 && !$objectifManager->isExist(intval($objectifNameOrId))) {
    $erreurs["objectif"] = "L'objectif selectionné est incorrect.";
}

//Si la valeur numérique est égale à 0 alors il s'agit d'un nouveau secteur d'activité
if (intval($secteurNameOrId) == 0 && $secteurManager->isNomExist($secteurNameOrId)) {
    $erreurs["secteur"] = "Le secteur d'activité existe déjà.";
} else if (intval($secteurNameOrId) == 0 && !$secteurManager->isNomExist($secteurNameOrId)) {
    try {
        Functions::validTexte($secteurNameOrId, "secteur", 3, 30, true);
    } catch(Exception $e) {
        $erreurs["secteur"] = $e->getMessage();
    }
} else if (intval($secteurNameOrId) != 0 && !$secteurManager->isExist(intval($secteurNameOrId))) {
    $erreurs["secteur"] = "Le secteur d'activité selectionné est incorrect.";
}


if (empty($erreurs)) {

    $entreprise = new Entreprise();
    $nombreEmploye = new NombreEmploye();
    $objectif = new Objectif();
    $secteur = new Secteur();

    //on enregistre le nouvel objectif
    if (intval($objectifNameOrId) == 0) {
        $objectif->setNom(ucfirst($objectifNameOrId));
        $objectifId = $objectifManager->addObjectif($objectif);
        $objectif->setId($objectifId);
    }

    //on enregistre le nouveau secteur
    if (intval($secteurNameOrId) == 0) {
        $secteur->setNom(ucfirst($secteurNameOrId));
        $secteurId = $secteurManager->addSecteur($secteur);
        $secteur->setId($secteurId);
    }

    $nombreEmploye->setId(intval($nombreEmployeId));

    $entreprise->setNom(ucfirst($entrepriseNom));
    $entreprise->setNombreEmploye($nombreEmploye);
    $entreprise->setObjectif($objectif);
    $entreprise->setSecteur($secteur);
    $entrepriseId = $entrepriseManager->addEntreprise($entreprise);
    $entreprise->setId($entrepriseId);

    if ($entrepriseId != null) {

        // Mot de passe total généré
        $passwordGenerated = Functions::generatePassword(Constants::PASSWORD_LENGTH);
        $passwordGeneratedAndSalt = Constants::PASSWORD_GENERATE_START_SALT . $passwordGenerated . Constants::PASSWORD_GENERATE_END_SALT;
        $passwordCrypte = password_hash($passwordGeneratedAndSalt, PASSWORD_DEFAULT);

        // Generer un token
        $token = md5($email);

        // Formater les mots
        $nom = ucfirst(strtolower($nom));
        $prenoms = ucwords(strtolower($prenoms));

        $utilisateur = new Utilisateur();
        $typeCompte = new TypeCompte();

        $typeCompte->setId(Constants::COMPTE_ENTREPRISE);

        $dateInscription = new DateTime();
        $dateInscription = $dateInscription->format("Y/m/d");

        $utilisateur->setNom($nom);
        $utilisateur->setPrenoms($prenoms);
        $utilisateur->setEmail($email);
        $utilisateur->setTelephone($telephone);
        $utilisateur->setTypeCompte($typeCompte);
        $utilisateur->setPassword($passwordCrypte);
        $utilisateur->setToken($token);
        $utilisateur->setEntreprise($entreprise);
        $utilisateur->setDateInscription($dateInscription);


        //Envoyer un mail
        $objet = "Création d'un compte entreprise GEHANT";
        $message = "<h3 style='text-align:center'>Bonjour " . $prenoms . "</h3>
                            <p style='text-align:justify;'>Vous y êtes.<br />
                                Plus qu'une dernière étape et vous pourrez bénéficier de vos formations<br />
                                Vous trouverez ci-dessous vos identifiants de connexion à votre compte entreprise GEHANT</p>
                            <p style='margin-bottom:30px;'>Email : " . $email . "<br /> Mot de passe : " . $passwordGenerated . "</p>
                            <p style='text-align:center;margin-bottom:30px;'>
                                <a href=\"http://" . $_SERVER["HTTP_HOST"] . "/activation/" . $token . "\" style=\"text-decoration:none;padding:15px;color:#fff;background:#f07b16\">
                                    Activez votre compte maintenant
                                </a>
                            </p>
                <p>Si vous ne parvenez pas à activer en cliquant sur le bouton, copiez puis collez le lien suivant dans votre navigateur.</p>
                <p>http://" . $_SERVER["HTTP_HOST"] . "/activation/" . $token . "</p>
                    <p style='text-align:justify'>Activez votre compte et apprenez en toute simplicité.</p>
                    <p>A bientôt,<br />L'équipe GEHANT</p>";


        $altMessage = "Bonjour " . $prenoms . "\n\nVous y êtes.\nPlus qu'une dernière étape et vous pourrez bénéficier de vos formations
                                Vous trouverez ci-dessous vos identifiants de connexion à votre compte entreprise GEHANT\n\n
                            Email : " . $email . "\n Mot de passe : " . $passwordGenerated . "\n\n
                             Copiez-collez le lien suivant dans votre navigateur pour activer votre compte\n
                                http://" . $_SERVER["HTTP_HOST"] . "/activation/" . $token . "\"\n\n
                   Activez votre compte et apprenez en toute simplicité.\n\nA bientôt,\nL'équipe GEHANT";


        try {
            if(Functions::sendMail($email, $objet, $message, $altMessage)) {
                // inscription
                $utilisateurId = $utilisateurManager->inscription($utilisateur, false);

                $entrepriseManager->updateEntrepriseUtilisateur($utilisateurId, $entreprise->getId());

                echo json_encode(array("type" => "success"));
            }else{
                echo json_encode(array("mail" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."));
                exit();
            }

        } catch(Exception $e) {

            echo json_encode(array("msg" => $e->getMessage()));
            exit();
        }
    }else{
        echo json_encode(array("msg" => "Une erreur est survenue. Merci de réessayer ultérieurement."));
    }

} else {
    echo json_encode($erreurs);
}
