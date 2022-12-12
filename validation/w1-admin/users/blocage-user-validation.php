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
$motif = Functions::getValueChamp($_POST["motif"]);

$erreurs = array();

if($id == null || intval($id) == 0){
    $erreurs["id"] = "Une erreur est survenue, veuillez réessayer ultérieurement.";
}

try {
    Functions::validTexte($motif, "motif", 10, 250, true);
}catch (Exception $e){
    $erreurs["motif"] = $e->getMessage();
}

if(empty($erreurs)){
    $utilisateur = new Utilisateur();
    $utilisateurManager = new UtilisateurManager();
    
    $dateBlocage = new DateTime();
    $dateBlocage = $dateBlocage -> format("Y-m-d");
    
    $utilisateur->setId(intval($id));
    $utilisateur->setBloquer(true);
    $utilisateur->setMotifBlocage(ucfirst($motif));
    $utilisateur->setDateBlocage($dateBlocage);
    
    if($utilisateurManager->bloquerAndDebloquer($utilisateur)){
         echo json_encode(array(
         	"type" => "success"
        	));
    }
    
}else{
    echo json_encode($erreurs);
}