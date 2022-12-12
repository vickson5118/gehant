<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

use utils\Functions;
use manager\UtilisateurManager;
use utils\Constants;

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
    
    $utilisateurManager = new UtilisateurManager();
    
    if($utilisateurManager->defineAndAnnulerAdmin(intval($id),Constants::COMPTE_STANDARD )){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}