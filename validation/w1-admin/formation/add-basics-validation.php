<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Domaine.php");

use utils\Constants;
use utils\Functions;
use manager\DomaineManager;
use manager\FormationManager;
use src\Domaine;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$titre = Functions::getValueChamp($_POST["titre"]);
$but = Functions::getValueChamp($_POST["but"]);
$domaineId = Functions::getValueChamp($_POST["domaine"]);
$prix = Functions::getValueChamp($_POST["prix"]);
$description = Functions::getValueChamp($_POST["description"]);
$dateDebut = Functions::getValueChamp($_POST["debut"]);
$dateFin = Functions::getValueChamp($_POST["fin"]);
$lieu = Functions::getValueChamp($_POST["lieu"]);
$formationIllustration = $_FILES["formationIllustration"];

$domaineManager = new DomaineManager();
$formationManager = new FormationManager();

$formation = $_SESSION["formation"];
$auteur = $_SESSION["utilisateur"];

$extension = null;
$changedLocation = null;
$domaineUrl = null;
$erreurs = array();

try {
    Functions::validFormationTitreExist($titre, $formation->getId(), $formationManager);
} catch (Exception $e) {
    $erreurs["titre"] = $e->getMessage();
}

try {
    Functions::validTexte($but, "but", 5, 80, true);
} catch (Exception $e) {
    $erreurs["but"] = $e->getMessage();
}

try {
    Functions::validTexte($lieu, "lieu de formation", 2, 21, true);
} catch (Exception $e) {
    $erreurs["lieu"] = $e->getMessage();
}

try {
    $domaineUrl = Functions::validDomaineFormation(intval($domaineId), $domaineManager);
} catch (Exception $e) {
    $erreurs["domaine"] = $e->getMessage();
}

try {
    Functions::validPrix($prix);
} catch (Exception $e) {
    $erreurs["prix"] = $e->getMessage();
}

try {
    Functions::validTexte($description, "description", 100, 500, true);
} catch (Exception $e) {
    $erreurs["description"] = $e->getMessage();
}


try{
    //On s'assure que la date de debut de la formation spécifiée est supérieure à 7 jours
    if($dateDebut == null){
        throw new Exception("Le champ date de début ne peut être vide.");
    }else if($formation->getDateDebut() == null){
       $now = new DateTime();
       $dateDebutConvert = new DateTime(Functions::convertDateFrToEn($dateDebut));
       $intervalDebut = $dateDebutConvert->diff($now);
       if(intval($intervalDebut->format("%a")) < 6){
           throw new Exception("La date de début de la formation doit être 7 jours plutard.");
       }
    }
    Functions::validDate($dateDebut);
} catch(Exception $e){
    $erreurs["debut"] = $e -> getMessage();
}


try{
    //On s'assure que la date de fin de la formation est bien supérieure à celle de debut
    if($dateFin == null){
        throw new Exception("Le champ date de fin ne peut être vide.");
    }else{
        $dateDebutConvert = new DateTime(Functions::convertDateFrToEn($dateDebut));
        $dateFinConvert = new DateTime(Functions::convertDateFrToEn($dateFin));
        
        if($dateDebutConvert >= $dateFinConvert){
            throw new Exception("La date de fin de la formation ne peut pas être inférieur ou égale à la date de début.");
        }
    }
    Functions::validDate($dateFin);
} catch(Exception $e){
    $erreurs["fin"] = $e -> getMessage();
}


if(empty($erreurs)){
    
    /*On s'assure que le titre et/ou le domaine n'a pas changé si c'est le cas alors l'adresse URL doit changer, elle aussi,*/
    if($formation->getTitre() != $titre || $formation->getDomaine()->getId() != intval($domaineId)){
        $changedLocation = "/w1-admin/creation/formation/".$domaineUrl."/".Functions::formatUrl(strtolower($titre))."/presentation";
    }
    
  /*  $dateDebutTab = explode("/", $dateDebut);
    $dateFinTab = explode("/", $dateFin);*/
    
   $domaine = new Domaine();
    
    $titreSansAccent = Functions::formatUrl(strtolower($titre));
    $domaine->setId(intval($domaineId));
    
    $formation->setTitre(ucfirst($titre));
    $formation->setTitreUrl($titreSansAccent);
    $formation->setBut(ucfirst($but));
    $formation->setDescription(ucfirst($description));
    $formation->setPrix(intval($prix));
    $formation->setDomaine($domaine);
    $formation->setAuteur($auteur);
    $formation->setLieu(ucfirst(strtolower($lieu)));
    $formation->setDateDebut(Functions::convertDateFrToEn($dateDebut));
    $formation->setDateFin(Functions::convertDateFrToEn($dateFin));
    
    //Verifier s'il y a une image dans le post dans ce cas, nous faisons les verification d'usage
    if(!empty($formationIllustration["name"]) ){
        try {
            $extension = Functions::validImage($formationIllustration, Constants::TAILLE_ILLUSTRATION, "10"
                    , Constants::ILLUSTRATION_WIDTH, Constants::ILLUSTRATION_HEIGHT);
        } catch (Exception $e) {
            $erreurs["formationIllustration"] = $e->getMessage();
            echo json_encode($erreurs);
            exit();
        }
        
        $folderDestination = $_SERVER["DOCUMENT_ROOT"].Constants::FORMATION_ILLUSTRATION_FOLDER.$titreSansAccent.".".$extension;
        $formation->setIllustration(Constants::FORMATION_ILLUSTRATION_FOLDER.$titreSansAccent.".".$extension);
        
        if(move_uploaded_file($formationIllustration["tmp_name"], $folderDestination) && $formationManager->updateBasicsWithImageFile($formation)){
            
            if($changedLocation != null){
                
                echo json_encode(array(
                    "location" => $changedLocation
                ));
                
            }else{
                echo json_encode(array(
                    "type" => "success"
                ));
            }
        }
     
        //S'il n'y a pas d'image dans le post et quil n'y pas d'image enregistre en bDD
    }elseif($formation->getIllustration() == null){
        $erreurs["formationIllustration"] = "L'image d'illustration de la formation est inexistant.";
        echo json_encode($erreurs);
        //s'il n'y a pas d'image, mais une image en BDD
    }else{
        if($formationManager->updateBasicsWithoutImageFile($formation)){
            
            if($changedLocation != null){
                
                echo json_encode(array(
                    "location" => $changedLocation
                ));
                
            }else{
                echo json_encode(array(
                    "type" => "success"
                ));
            }
            
        }
    }
    
    
    
}else{
   echo json_encode($erreurs);
}




