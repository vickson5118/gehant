<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");

use utils\Functions;
use manager\DomaineManager;
use src\Formation;
use src\Domaine;
use manager\FormationManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$domaineId = Functions::getValueChamp($_POST["domaine"]);
$titre = Functions::getValueChamp($_POST["titre"]);

$domaineManager = new DomaineManager();
$formationManager = new FormationManager();
$erreurs = array();


$domaineUrl = null;

try {
    $domaineUrl = Functions::validDomaineFormation(intval($domaineId), $domaineManager);
} catch (Exception $e) {
    $erreurs["domaine"] = $e->getMessage();
}

try {
    Functions::validFormationTitre($titre, $formationManager);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}

if(empty($erreurs)){
    $formation = new Formation();
    $domaine = new Domaine();
    $auteur = $_SESSION["utilisateur"];
    
    $titreSansAccent = Functions::formatUrl(strtolower($titre));
    $dateCreation = new DateTime();
    $dateCreation = $dateCreation -> format("Y/m/d");
    
    $domaine->setId(intval($domaineId));
    $domaine->setTitreUrl($domaineUrl);
    
    $formation->setTitre(ucfirst($titre));
    $formation->setTitreUrl($titreSansAccent);
    $formation->setDomaine($domaine);
    $formation->setAuteur($auteur);
    $formation->setDateCreation($dateCreation);
    
    if($formationManager->addFormation($formation)){
        $domaineManager->addFormationToDomaine(intval($domaineId));
        $_SESSION["formation"] = $formation;
        echo json_encode(array(
            "location" => "/w1-admin/creation/formation/".$domaineUrl."/".$titreSansAccent
        ));
    }
    
    
}else{
    echo json_encode($erreurs);
}