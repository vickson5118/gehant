<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

use utils\Constants;
use utils\Functions;
use manager\UtilisateurManager;

$email = Functions::getValueChamp($_POST["email"]);
$password = Functions::getValueChamp($_POST["password"]);

$password = Constants::PASSWORD_GENERATE_START_SALT . $password . Constants::PASSWORD_GENERATE_END_SALT;

$utilisateurManager = new UtilisateurManager();

$utilisateur = $utilisateurManager -> connexionAdmin($email);

if($utilisateur != null && password_verify($password, $utilisateur->getPassword())){
    
    if($utilisateur->isBloquer()){
        echo json_encode(array(
            "error" => "Votre compte a été bloqué par Gehant."
        ));
        exit();
    }
    
    session_start ();
    $_SESSION ["utilisateur"] = $utilisateur;
    
    $now = new DateTime();
    $now = $now->format("Y-m-d");
    
    $utilisateurManager->updateConnect($utilisateur->getId(), $now);
    
    echo json_encode(array(
        "type" => "success"
    ));
    
}else{
    
    echo json_encode(array(
        "error" => "L'adresse E-mail et/ou le mot de passe est incorrect."
    ));
    
}