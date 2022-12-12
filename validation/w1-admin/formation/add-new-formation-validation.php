<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");

use manager\FormationManager;
use manager\DomaineManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$formationManager = new FormationManager();
$domaineManager = new DomaineManager();
$formation = $_SESSION["formation"];

$dateCreation = new DateTime();
$dateCreation = $dateCreation -> format("Y-m-d");

$formation->setDateCreation($dateCreation);

if($formationManager->redactionFinished($formation) && 
    $domaineManager->updateNombreFormationRedactionAndActive($formation)){
     echo json_encode(array(
     	"type" => "success"
    	));
}else{
    echo json_encode(array(
        "msg" => "Une erreur est survenue, verifier votre connexion et réessayer ultérieurement."
    ));
}