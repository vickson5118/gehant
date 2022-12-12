<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

use utils\Functions;
use manager\FormationManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$prerequis = Functions::getValueChamp($_POST["prerequis"]);
$cibles = Functions::getValueChamp($_POST["cibles"]);
$objectifs = Functions::getValueChamp($_POST["objectifs"]);
$points = Functions::getValueChamp($_POST["points"]);

$formation = $_SESSION["formation"];

$formationManager = new FormationManager();

if(isset($_POST["prerequis"])){
    if($prerequis != null){
        $formation->setPrerequis(ucwords(rtrim($prerequis,";"),";"));
    }
    if($formationManager->updateFormationPrerequis($formation)){
        
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}elseif (isset($_POST["cibles"])){
    if($cibles != null){
        $formation->setCibles(ucwords(rtrim($cibles,";"),";"));
    }
    if($formationManager->updateFormationCibles($formation)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
}elseif (isset($_POST["objectifs"])){
    if($objectifs != null){
        $formation->setObjectifs(ucwords(rtrim($objectifs,";"),";"));
    }
    if($formationManager->updateFormationObjectifs($formation)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
}


