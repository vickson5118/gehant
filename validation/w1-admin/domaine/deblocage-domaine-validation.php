<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");

use manager\DomaineManager;
use utils\Functions;
use src\Domaine;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}


$id = Functions::getValueChamp($_POST["id"]);

$erreurs = array();

if($id == null || intval($id) == 0){
    $erreurs["id"] = "Une erreur est survenue, veuillez réessayer ultérieurement.";
}


if(empty($erreurs)){
    $domaine = new Domaine();
    $domaineManager = new DomaineManager();
    
    $domaine->setId(intval($id));
    $domaine->setBloquer(false);
    $domaine->setMotifBlocage(null);
    $domaine->setDateBlocage(null);
    
    if($domaineManager->bloquerAndDebloquer($domaine)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}