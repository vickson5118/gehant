<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PointCleManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Module.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/PointCle.php");

use utils\Functions;
use manager\PointCleManager;
use src\Module;
use src\PointCle;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$moduleId = Functions::getValueChamp($_POST["id"]);
$point = Functions::getValueChamp($_POST["point"]);

$erreurs = array();

try {
    Functions::validTexte($point, "titre du point clé", 10, 50, true);
} catch (Exception $e) {
    $erreurs["point"] = $e->getMessage();
}

if(intval($moduleId) <= 0 || intval($moduleId) == null){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    $PointCleManager = new PointCleManager();
    $pointCle = new PointCle();
    $module = new Module();
    
    $module->setId(intval($moduleId));
    
    $pointCle->setTitre(ucfirst($point));
    $pointCle->setModule($module);
    
    if($PointCleManager->addPointCle($pointCle)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
    
}else{
    echo json_encode($erreurs);
}