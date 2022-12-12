<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ModuleManager.php");

use utils\Functions;
use manager\ModuleManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$moduleId = Functions::getValueChamp($_POST["id"]);

$erreurs = array();

if(intval($moduleId) == 0){
    $erreurs["id"] = "Une erreur est survenue, veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    
    $moduleManager = new ModuleManager();
    
    if($moduleManager->deleteModule(intval($moduleId))){
         echo json_encode(array(
         	"type" => "success"
        	));
    }
    
}else{
    echo json_encode($erreurs);
}