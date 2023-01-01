<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FactureManager.php");

use manager\FactureManager;
use utils\Functions;

session_start();

if(($_SESSION["utilisateur"]) == null){
    echo json_encode(array(
        "type" => "session"));
    exit();
}

$achatId = Functions::getValueChamp($_POST["id"]);

$erreurs = array();

if(intval($achatId) == 0){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    $factureManager = new FactureManager();
    
    $factureManager->deleteFacture(intval($achatId));
    echo json_encode(array(
        "type" => "success"
    ));
    
}else{
    echo json_encode($erreurs);
}