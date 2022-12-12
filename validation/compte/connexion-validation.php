<?php

namespace validation\compte;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");

use manager\UtilisateurManager;
use utils\Constants;
use DateTime;


$email = strip_tags(trim($_POST["email"]));
$password = strip_tags(trim($_POST["password"]));

$password = Constants::PASSWORD_GENERATE_START_SALT . $password . Constants::PASSWORD_GENERATE_END_SALT;

$utilisateurManager = new UtilisateurManager();

$utilisateur = $utilisateurManager -> connexion($email,false);

if($utilisateur != null && password_verify($password, $utilisateur->getPassword())){
    
    if($utilisateur->isBloquer()){
        echo json_encode(array(
            "error" => "Votre compte a été bloqué par GEHANT."
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
        "error" => "L'adresse E-mail et/ou le mot de passe est incorrect"
    ));
    
}