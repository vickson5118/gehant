<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");

use utils\Functions;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
     echo json_encode(array(
     	"type" => "session"
    	));
     exit();
}

$email = Functions::getValueChamp($_POST["email"]);
$objet = Functions::getValueChamp($_POST["objet"]);
$message = Functions::getValueChamp($_POST["message"]);

$erreurs = array();

try {
    Functions::validTexte($objet, "objet", 15, 150, true);
} catch (Exception $e) {
    $erreurs["objet"] = $e->getMessage();
}

try {
    Functions::validTexte($message, "message", 20, 1500, true);
} catch (Exception $e) {
    $erreurs["message"] = $e->getMessage();
}

if($email == null){
    $erreurs["email"] = "Une erreur est survenue lors de l'envoi du mail, veuillez réessayer ultérieurement.";
}

if(empty($erreurs)){
    
    try {
        
        if(Functions::sendMail($email, ucfirst($objet), ucfirst($message), ucfirst($message))){
             echo json_encode(array(
             	"type" => "success"
            	));
        }else{
            $erreurs["email"] = "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement.";
            echo json_encode($erreurs);
            exit();
        }
        
    }catch (Exception $e){
        $erreurs["email"] = $e->getMessage();
        echo json_encode($erreurs);
    }
    
}else{
    echo json_encode($erreurs);
}