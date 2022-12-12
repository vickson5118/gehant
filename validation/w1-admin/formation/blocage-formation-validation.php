<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");

use utils\Functions;
use src\Formation;
use manager\FormationManager;
use manager\DomaineManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$id = Functions::getValueChamp($_POST["id"]);
$motif = Functions::getValueChamp($_POST["motif"]);
$domaineUrl = Functions::getValueChamp($_POST["url"]) == null ? $_SESSION["domaineUrl"] : Functions::getValueChamp($_POST["url"]);

$erreurs = array();

if($id == null || intval($id) == 0){
    $erreurs["id"] = "Une erreur est survenue, veuillez réessayer ultérieurement.";
}

try {
    Functions::validTexte($motif, "motif", 10, 250, true);
}catch (Exception $e){
    $erreurs["motif"] = $e->getMessage();
}

if(empty($erreurs)){
    $domaineManager = new DomaineManager();
    $formationManager = new FormationManager();
    $formation = new Formation();
    
    
    $dateBlocage = new DateTime();
    $dateBlocage = $dateBlocage -> format("Y-m-d");
    
    $formation->setId(intval($id));
    $formation->setBloquer(true);
    $formation->setMotifBlocage(ucfirst($motif));
    $formation->setDateBlocage($dateBlocage);
    
    if($formationManager->bloquerAndDebloquer($formation) &&
        $domaineManager->updateNombreFormationActiveAndBloquer($domaineUrl,true)){
        
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}