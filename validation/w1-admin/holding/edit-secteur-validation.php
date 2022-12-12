<?php


require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/SecteurManager.php");


use utils\Functions;
use manager\SecteurManager;
use src\Secteur;

session_start();

if(($_SESSION["utilisateur"]) == null){
    echo json_encode(array(
        "type" => "session"));
    exit();
}

$secteurManager = new SecteurManager();

$id = Functions::getValueChamp($_POST["id"]);
$nom = Functions::getValueChamp($_POST["nom"]);

$erreurs = array();

$id = intval($id);

if($id == 0){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
    exit();
}

if($secteurManager -> isNomExistWithoutThis($nom, $id)){
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
    $secteur->setId($id);
    
    if($secteurManager->updateSecteur($secteur)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
} else{
    echo json_encode($erreurs);
}



