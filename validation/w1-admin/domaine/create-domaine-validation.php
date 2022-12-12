<?php


require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");

use utils\Functions;
use utils\Constants;
use src\Domaine;
use manager\DomaineManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$titre = Functions::getValueChamp($_POST["titre"]);
$description = Functions::getValueChamp($_POST["description"]);
$domaineIllustration = $_FILES["domaineIllustration"];

$erreurs = array();
$extension = null;

try {
    Functions::validTexte($titre, "titre du domaine", 5, 30, true);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}

try {
    Functions::validTexte($description, "description", 100, 500, true);
} catch (Exception $e) {
    $erreurs["description"] = $e->getMessage();
}

try {
    $extension = Functions::validImage($domaineIllustration, Constants::TAILLE_ILLUSTRATION, "10", 
        Constants::ILLUSTRATION_WIDTH, Constants::ILLUSTRATION_HEIGHT);
} catch (Exception $e) {
    $erreurs["domaineIllustration"] = $e->getMessage();
}


if(empty($erreurs)){
    
    $titreSansAccent = Functions::formatUrl(strtolower($titre));
    $folderDestination = $_SERVER["DOCUMENT_ROOT"].Constants::DOMAINE_ILLUSTRATION_FOLDER.$titreSansAccent.".".$extension;
    
    $dateCreation = new DateTime();
    $dateCreation = $dateCreation -> format("Y/m/d");
    
    $domaineManager = new DomaineManager();
    $domaine = new Domaine();
    
    $domaine->setTitre(ucfirst($titre));
    $domaine->setDescription(ucfirst($description));
    $domaine->setTitreUrl($titreSansAccent);
    $domaine->setIllustration(Constants::DOMAINE_ILLUSTRATION_FOLDER.$titreSansAccent.".".$extension);
    $domaine->setDateCreation($dateCreation);
    
    if(move_uploaded_file($domaineIllustration["tmp_name"], $folderDestination) && $domaineManager->addDomaine($domaine)){
        echo json_encode(array(
            "type" => "success"
        ));
    }

}else{
    echo json_encode($erreurs);
}