<?php

namespace validation\entreprise;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Entreprise.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Formation.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/FormationManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");

use utils\Functions;
use manager\FormationManager;
use manager\UtilisateurManager;
use src\Entreprise;
use src\Achat;
use manager\AchatManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"));
    exit();
}


$formationId = Functions::getValueChamp($_POST["formationId"]);
$utilisateurId = Functions::getValueChamp($_POST["userId"]);


if(intval($formationId) == 0 || intval($utilisateurId) == 0){
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
    exit();
}


$formationManager = new FormationManager();
$utilisateurManager = new UtilisateurManager();

//recuperation de la formation et de l'utilisateur
$formation = $formationManager->getOneFormationInfoById(intval($formationId));
$utilisateur = $utilisateurManager->getUtilisateurById(intval($utilisateurId));


if( $formation == null || $utilisateur == null){
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
    exit();
}


$achat = new Achat();
$entreprise = new Entreprise();

$entreprise -> setId($_SESSION["utilisateur"] -> getEntreprise()  -> getId());

$utilisateur -> setId(intval($utilisateurId));
$formation -> setId(intval($formationId));


$achat -> setEntreprise($entreprise);
$achat -> setUtilisateur($utilisateur);
$achat -> setFormation($formation);

$achatManager = new AchatManager();
    
if($achatManager -> addFormation($achat)){
    echo json_encode(array(
        "type" => "success"));
}else{
    echo json_encode(array(
        "msg" => "Une erreur est survenue, veuillez réessayer ultérieurement."));
    exit();
}
    




    
    
    
    