<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

use utils\Functions;
use utils\Constants;
use manager\UtilisateurManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$profilPicture = $_FILES["profilFile"];

const PROFIL_PICTURE_WIDTH_HEIGHT = 100;

const PROFIL_PICTURE_TAILLE = 5000000;


if(!empty($profilPicture["name"]) ){
    
    try {
        $extension = Functions::validImage($profilPicture, PROFIL_PICTURE_TAILLE, "5",
            PROFIL_PICTURE_WIDTH_HEIGHT, PROFIL_PICTURE_WIDTH_HEIGHT);
        
        $folderDestination = $_SERVER["DOCUMENT_ROOT"].Constants::PROFIL_FOLDER.$_SESSION["utilisateur"]->getId().".".$extension;
        $_SESSION["utilisateur"]->setProfilPicture(Constants::PROFIL_FOLDER.$_SESSION["utilisateur"]->getId().".".$extension);
        
        $utilisateurManager = new UtilisateurManager();
        
        if(move_uploaded_file($profilPicture["tmp_name"], $folderDestination) && $utilisateurManager->updateProfilPicture($_SESSION["utilisateur"])){
            echo json_encode(array(
                "type" => "success"
            ));
        }
    } catch (Exception $e) {
        $_SESSION["utilisateur"]->setProfilPicture(null);
        echo json_encode(array(
            "msg" => $e->getMessage()
        ));
        
        exit();
    }
    
}else{
    $_SESSION["utilisateur"]->setProfilPicture(null);
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."
    ));
}

