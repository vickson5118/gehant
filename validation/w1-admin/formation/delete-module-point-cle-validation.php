<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PointCleManager.php");

use utils\Functions;
use manager\PointCleManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$pointId = Functions::getValueChamp($_POST["id"]);

$erreurs = array();

if(intval($pointId) == 0){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    $PointCleManager = new PointCleManager();
    
    if($PointCleManager->deletePointCle(intval($pointId))){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
    
}else{
    echo json_encode($erreurs);
}