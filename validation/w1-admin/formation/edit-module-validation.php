<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ModuleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Module.php");

use utils\Functions;
use src\Module;
use manager\ModuleManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$moduleId = Functions::getValueChamp($_POST["id"]);
$titre = Functions::getValueChamp($_POST["titre"]);


$erreurs = array();

try {
    Functions::validTexte($titre, "titre", 10, 100, true);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}

if(intval($moduleId) <= 0 || intval($moduleId) == null){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    $moduleManager = new ModuleManager();
    $module = new Module();
    
    $module->setId(intval($moduleId));
    $module->setTitre(ucfirst($titre));
    
    if($moduleManager->updateModule($module)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
    
}else{
    echo json_encode($erreurs);
}