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

$id = Functions::getValueChamp($_POST["id"]);
$titre = Functions::getValueChamp($_POST["editTitre"]);
$description = Functions::getValueChamp($_POST["editDescription"]);
$domaineIllustration = $_FILES["editDomaineIllustration"];
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
    Functions::validTexte($titre, "titre du domaine", 5, 30, true);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}

try {
    Functions::validTexte($description, "description", 100, 500, true);
} catch (Exception $e) {
    $erreurs["description"] = $e->getMessage();
}

/*if($domaineIllustration == null && $oldIllustrationPath == null){
    
}*/

if(!empty($domaineIllustration["name"])){
    try {
        $extension = Functions::validImage($domaineIllustration, Constants::TAILLE_ILLUSTRATION, "10", 
            Constants::ILLUSTRATION_WIDTH, Constants::ILLUSTRATION_HEIGHT);
    } catch (Exception $e) {
        $erreurs["domaineIllustration"] = $e->getMessage();
    }
}

if(empty($erreurs)){
    
    $titreSansAccent = Functions::formatUrl(strtolower($titre));
    $folderDestination = $_SERVER["DOCUMENT_ROOT"].Constants::DOMAINE_ILLUSTRATION_FOLDER.$titreSansAccent.".".$extension;

    $domaineManager = new DomaineManager();
    $domaine = new Domaine();
    
    $domaine->setId(intval($id));
    $domaine->setTitre(ucfirst($titre));
    $domaine->setDescription(ucfirst($description));
    $domaine->setTitreUrl($titreSansAccent);
    $domaine->setIllustration(Constants::DOMAINE_ILLUSTRATION_FOLDER.$titreSansAccent.".".$extension);
    
    if(empty($domaineIllustration["name"])){
    
        //MAJ sans l'image d'illustration puisque deja en BDD
        $domaineManager->updateDomaineWithoutImage($domaine);
        echo json_encode(array(
            "type" => "success"
        ));
        
    }else{
        //supprimer l'ancienne illustration du domaine
        unlink($_SERVER["DOCUMENT_ROOT"].$oldIllustrationPath);
        if(move_uploaded_file($domaineIllustration["tmp_name"], $folderDestination) && 
            $domaineManager->updateDomaineWithImage($domaine)){
            echo json_encode(array(
                "type" => "success"
            ));
        }
        
        
    }
    

    
    
}else{
    echo json_encode($erreurs);
}