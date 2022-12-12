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

$nom = Functions::getValueChamp($_POST["nom"]);
$partenaireIllustration = $_FILES["partenaireIllustration"];

$erreurs = array();
$extension = null;

try {
    Functions::validTexte($nom, "nom de l'entreprise", 2, 30, true);
} catch (Exception $e) {
    $erreurs["nom"] = $e->getMessage();
}

try {
    $extension = Functions::validImage($partenaireIllustration, Constants::TAILLE_ILLUSTRATION, "10",50, 50);
} catch (Exception $e) {
    $erreurs["partenaireIllustration"] = $e->getMessage();
}

if(empty($erreurs)){
    
    $partenaireManager = new PartenaireManager();
    $partenaire = new Partenaire();
    
    $nomSansAccent = Functions::formatUrl($nom);
    $folderDestination = $_SERVER["DOCUMENT_ROOT"].Constants::PARTENAIRE_FOLDER.$nomSansAccent.".".$extension;
    
    $partenaire->setNom(strtoupper($nom));
    $partenaire->setLogo(Constants::PARTENAIRE_FOLDER.$nomSansAccent.".".$extension);
    
    if(move_uploaded_file($partenaireIllustration["tmp_name"], $folderDestination) && $partenaireManager->addPartenaire($partenaire)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}else{
    echo json_encode($erreurs);
}




