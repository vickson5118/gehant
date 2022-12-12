<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PartenaireManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Partenaire.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");

use utils\Functions;
use utils\Constants;
use src\Partenaire;
use manager\PartenaireManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$id = Functions::getValueChamp($_POST["id"]);
$nom = Functions::getValueChamp($_POST["editNom"]);
$partenaireIllustration = $_FILES["editPartenaireIllustration"];
$oldIllustrationPath = Functions::getValueChamp($_POST["oldIllustrationPath"]);

$erreurs = array();
$extension = null;

if(intval($id) == 0){
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."
    ));
    
    exit();
}

try {
    Functions::validTexte($nom, "nom de l'entreprise", 2, 30, true);
} catch (Exception $e) {
    $erreurs["nom"] = $e->getMessage();
}

if(!empty($partenaireIllustration["name"])){
    try {
        $extension = Functions::validImage($partenaireIllustration, Constants::TAILLE_ILLUSTRATION, "10",50,50);
    } catch (Exception $e) {
        $erreurs["partenaireIllustration"] = $e->getMessage();
    }
}

if(empty($erreurs)){
    
    $nomSansAccent = Functions::formatUrl($nom);
   
    
    $partenaireManager = new PartenaireManager();
    $partenaire = new Partenaire();
    
    $partenaire->setId(intval($id));
    $partenaire->setNom(strtoupper($nom));
    
    if(empty($partenaireIllustration["name"])){
        
        $partenaireManager->updatePartenaireWithoutLogo($partenaire);
        
    }else{  
        $folderDestination = $_SERVER["DOCUMENT_ROOT"].Constants::PARTENAIRE_FOLDER.$nomSansAccent.".".$extension;     
        
        $partenaire->setLogo(Constants::PARTENAIRE_FOLDER.$nomSansAccent.".".$extension);
        
        $partenaireManager->updatePartenaireWithLogo($partenaire);
        
        //supprimer l'ancienne illustration
        unlink($_SERVER["DOCUMENT_ROOT"].$oldIllustrationPath);

        if(move_uploaded_file($partenaireIllustration["tmp_name"], $folderDestination) && $partenaireManager->updatePartenaireWithLogo($partenaire)){
            echo json_encode(array(
                "type" => "success"
            ));
            exit();
        }
        
    }
    
    echo json_encode(array(
        "type" => "success"
    ));
    
    
}else{
    echo json_encode($erreurs);
}


