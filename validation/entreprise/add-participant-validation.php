<?php

namespace validation\entreprise;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");


use utils\Constants;
use utils\Functions;
use Exception;
use DateTime;
use manager\UtilisateurManager;
use manager\FormationManager;
use src\Utilisateur;
use src\TypeCompte;
use src\Entreprise;
use manager\AchatManager;
use src\Achat;

session_start();


if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"));
    exit();
}


$nom = Functions::getValueChamp($_POST["nom"]);
$prenoms = Functions::getValueChamp($_POST["prenoms"]);
$email = Functions::getValueChamp($_POST["email"]);
$telephone = Functions::getValueChamp($_POST["telephone"]);
$fonction = Functions::getValueChamp($_POST["fonction"]);
$formationId = Functions::getValueChamp($_POST["formationId"]);


$erreurs = array();
$utilisateurManager = new UtilisateurManager();


try{
    Functions::validTexte($nom, "nom", 2, 30, true);
} catch(Exception $e){
    $erreurs["nom"] = $e -> getMessage();
}


try{
    Functions::validTexte($prenoms, "prenoms", 2, 150, true);
} catch(Exception $e){
    $erreurs["prenoms"] = $e -> getMessage();
}


try{
    Functions::validTexte($fonction, "fonction", 5, 30, true);
} catch(Exception $e){
    $erreurs["fonction"] = $e -> getMessage();
}


try{
    Functions::validEmail($email, $utilisateurManager);
} catch(Exception $e){
    $erreurs["email"] = $e -> getMessage();
}


try{
    Functions::validTelephone($telephone);
} catch(Exception $e){
    $erreurs["telephone"] = $e -> getMessage();
}


if($formationId == null || intval($formationId) == 0){
    echo json_encode(array(
        "msg" => "Une erreur est survenue, veuillez réessayer ultérieurement."));
    exit();
}


if(empty($erreurs)){
    
    // Mot de passe total généré
    $passwordGenerated = Functions::generatePassword(Constants::PASSWORD_ADMIN_LENGTH);
    $passwordGeneratedAndSalt = Constants::PASSWORD_GENERATE_START_SALT . $passwordGenerated . Constants::PASSWORD_GENERATE_END_SALT;
    $passwordCrypte = password_hash($passwordGeneratedAndSalt, PASSWORD_DEFAULT);

    // formater les mots
    $nom = ucfirst(strtolower($nom));
    $prenoms = ucwords(strtolower($prenoms));
    
    // recuperation de la formation
    $formationManager = new FormationManager();
    $formation = $formationManager -> getOneFormationInfoById(intval($formationId));
    
    // Creation de l'utilisateur
    $dateInscription = new DateTime();
    $dateInscription = $dateInscription -> format("Y-m-d");
    
    $utilisateur = new Utilisateur();
    $typeCompte = new TypeCompte();
    $entreprise = new Entreprise();
    
    $typeCompte -> setId(Constants::COMPTE_STANDARD);
    
    $entreprise -> setId($_SESSION["utilisateur"]->getEntreprise()->getId());
    
    $utilisateur -> setNom($nom);
    $utilisateur -> setPrenoms($prenoms);
    $utilisateur -> setEmail($email);
    $utilisateur -> setPassword($passwordCrypte);
    $utilisateur -> setTypeCompte($typeCompte);
    $utilisateur -> setFonction(ucfirst($fonction));
    $utilisateur -> setActive(true);
    $utilisateur -> setTelephone($telephone);
    $utilisateur -> setEntreprise($entreprise);
    $utilisateur -> setDateInscription($dateInscription);
    
    // inscription
    $utilisateurId = $utilisateurManager -> inscriptionParticipant($utilisateur);
    $utilisateur -> setId($utilisateurId);
    
    //enregistrement de l'achat
    $achatManager = new AchatManager();
    $achat = new Achat();
    
    $achat -> setEntreprise($entreprise);
    $achat -> setFormation($formation);
    $achat -> setUtilisateur($utilisateur);
    
    
    if($achatManager -> addFormation($achat)){   
      echo json_encode(array("type" => "success"));
    }else{
        echo json_encode(array(
            "msg" => "Une erreur est survenue, veuillez réessayer ultérieurement."));
        exit();
    }
    
} else{
    echo json_encode($erreurs);
}