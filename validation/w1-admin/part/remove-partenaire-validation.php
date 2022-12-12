<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PartenaireManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use utils\Functions;
use manager\PartenaireManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}


$id = Functions::getValueChamp($_POST["id"]);
$illustration = Functions::getValueChamp($_POST["illustration"]);

$erreurs = array();

if($id == null || intval($id) == 0){
    $erreurs["id"] = "Une erreur est survenue, veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    
    $partenaireManager = new PartenaireManager();
    
    $partenaireManager->removePartenaire(intval($id));
    
    if(!empty($illustration)){
        unlink($_SERVER["DOCUMENT_ROOT"].$illustration);
    }
    
    echo json_encode(array(
        "type" => "success"
    ));
    
    
}else{
    echo json_encode($erreurs);
}