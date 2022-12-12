<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

use utils\Functions;
use src\Utilisateur;
use manager\UtilisateurManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}


$id = Functions::getValueChamp($_POST["id"]);

$erreurs = array();

if($id == null || intval($id) == 0){
    $erreurs["id"] = "Une erreur est survenue, veuillez réessayer ultérieurement.";
}


if(empty($erreurs)){
    $utilisateur = new Utilisateur();
    $utilisateurManager = new UtilisateurManager();
    
    $utilisateur->setId(intval($id));
    $utilisateur->setBloquer(false);
    $utilisateur->setMotifBlocage(null);
    $utilisateur->setDateBlocage(null);
    
    if($utilisateurManager->bloquerAndDebloquer($utilisateur)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}