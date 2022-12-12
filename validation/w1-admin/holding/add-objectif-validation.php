<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/ObjectifManager.php");


use utils\Functions;
use manager\ObjectifManager;
use src\Objectif;

session_start();

if(($_SESSION["utilisateur"]) == null){
    echo json_encode(array(
        "type" => "session"));
    exit();
}

$objectifManager = new ObjectifManager();

$nom = Functions::getValueChamp($_POST["nom"]);

$erreurs = array();

if(!empty($nom) && $objectifManager -> isNomExist($nom)){
    $erreurs["objectif"] = "Le nom de l'objectif existe déja.";
} else{
    
    try{
        Functions::validTexte($nom, "objectif", 3, 30, true);
    } catch(Exception $e){
        $erreurs["objectif"] = $e -> getMessage();
    }
}

if(empty($erreurs)){
    
    $objectif = new Objectif();
    
    $objectif->setNom(ucfirst($nom));
    
    if($objectifManager->addObjectif($objectif)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
} else{
    echo json_encode($erreurs);
}