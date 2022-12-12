<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

use manager\UtilisateurManager;
use utils\Constants;
use utils\Functions;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$oldPassword = strip_tags(trim($_POST["oldPassword"]));
$password = strip_tags(trim($_POST["password"]));
$repeatPassword = strip_tags(trim($_POST["repeatPassword"]));

$erreurs = array();
$utilisateurManager = new UtilisateurManager();

$oldPassword = Constants::PASSWORD_GENERATE_START_SALT.$oldPassword.Constants::PASSWORD_GENERATE_END_SALT;

if(!password_verify($oldPassword, $_SESSION["utilisateur"]->getPassword())){
    $erreurs["oldPassword"] = "L'ancien mot de passe est incorrect.";
}

try {
    Functions::validPassword($password);
} catch (Exception $e) {
    $erreurs["password"] = $e->getMessage();
}

if($password != $repeatPassword){
    $erreurs["repeatPassword"] = "Les mots de passe ne sont pas identiques.";
}

if(empty($erreurs)){
    
    $password = Constants::PASSWORD_GENERATE_START_SALT.$password.Constants::PASSWORD_GENERATE_END_SALT;
    $passwordCrypte = password_hash($password, PASSWORD_DEFAULT);
    
    if($utilisateurManager->updatePassword($passwordCrypte, $_SESSION["utilisateur"]->getId())){
        
        session_destroy();
        
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}




