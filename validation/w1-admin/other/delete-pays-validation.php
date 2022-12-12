<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PaysManager.php");

use utils\Functions;
use manager\PaysManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$paysId = Functions::getValueChamp($_POST["id"]);

$paysManager = new PaysManager();
$erreurs = array();

if(intval($paysId) == 0){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
}


if(empty($erreurs)){
    
    if($paysManager->deletePays(intval($paysId))){
         echo json_encode(array(
         	"type" => "success"
        	));
    }
    
}else{
    
    echo json_encode($erreurs);
    
}

