<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");

use utils\Functions;
use manager\FormationManager;
use manager\DomaineManager;
use src\Formation;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$titreFormation = Functions::getValueChamp($_POST["titre"]);
$domaineUrl = $_SESSION["domaineUrl"];

$domaineManager = new DomaineManager();
$formationManager = new FormationManager();
$erreurs = array();

try {
    Functions::validFormationTitre($titreFormation, $formationManager);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}



if(empty($erreurs)){
    
    $formation = new Formation();
    $domaine = $domaineManager->getOneDomaine($domaineUrl);
    
    $auteur = $_SESSION["utilisateur"];
    
    $titreSansAccent = Functions::formatUrl(strtolower($titreFormation));
    
    $dateCreation = new DateTime();
    $dateCreation = $dateCreation -> format("Y/m/d");
    
    
    
    $formation->setTitre(ucfirst($titreFormation));
    $formation->setTitreUrl($titreSansAccent);
    $formation->setDomaine($domaine);
    $formation->setAuteur($auteur);
    $formation->setDateCreation($dateCreation);
    
    if($formationManager->addFormation($formation)){
        $domaineManager->addFormationToDomaine($domaine->getId());
        //pour la page d'édition
        $_SESSION["formation"] = $formation;
        
        echo json_encode(array(
            "location" => "/w1-admin/creation/formation/".$domaineUrl."/".$titreSansAccent
        ));
    }
    
    
}else{
    echo json_encode($erreurs);
}
