<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");

use src\Domaine;
use manager\DomaineManager;
use utils\Functions;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$id = Functions::getValueChamp($_POST["id"]);
$motif = Functions::getValueChamp($_POST["motif"]);

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
    $domaine = new Domaine();
    $domaineManager = new DomaineManager();
    
    $dateBlocage = new DateTime();
    $dateBlocage = $dateBlocage -> format("Y-m-d");
    
    $domaine->setId(intval($id));
    $domaine->setBloquer(true);
    $domaine->setMotifBlocage(ucfirst($motif));
    $domaine->setDateBlocage($dateBlocage);
    
    if($domaineManager->bloquerAndDebloquer($domaine)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}