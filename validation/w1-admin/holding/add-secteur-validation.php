<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/SecteurManager.php");

use manager\SecteurManager;
use utils\Functions;
use src\Secteur;

session_start();

if(($_SESSION["utilisateur"]) == null){
    echo json_encode(array(
        "type" => "session"));
    exit();
}

$nom = Functions::getValueChamp($_POST["nom"]);

$secteurManager = new SecteurManager();

$erreurs = array();

if(!empty($nom) && $secteurManager -> isNomExist($nom)){
    $erreurs["secteur"] = "Le nom du secteur d'activité existe déja.";
} else{
    
    try{
        Functions::validTexte($nom, "secteur d'activité", 3, 30, true);
    } catch(Exception $e){
        $erreurs["secteur"] = $e -> getMessage();
    }
}

if(empty($erreurs)){
    
    $secteur = new Secteur();
    
    $secteur->setNom(ucfirst($nom));
    
    if($secteurManager->addSecteur($secteur)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
} else{
    echo json_encode($erreurs);
}

