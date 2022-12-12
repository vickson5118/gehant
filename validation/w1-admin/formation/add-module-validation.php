<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ModuleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Module.php");

use utils\Functions;
use manager\ModuleManager;
use src\Module;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$titre = Functions::getValueChamp($_POST["titre"]);

$erreurs = array();

try {
    Functions::validTexte($titre, "titre", 10, 100, true);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}


if(empty($erreurs)){
    $moduleManager = new ModuleManager();
    $module = new Module();
    
    $formation = $_SESSION["formation"];
    
    $module->setTitre(ucfirst($titre));
    $module->setFormation($formation);
    
    if($moduleManager->addModule($module)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}