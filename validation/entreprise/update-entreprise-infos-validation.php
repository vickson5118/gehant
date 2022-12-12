<?php

namespace validation\entreprise;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/NombreEmploye.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Objectif.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Secteur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/EntrepriseManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/NombreEmployeManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/ObjectifManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/SecteurManager.php");


use utils\Functions;
use Exception;
use manager\NombreEmployeManager;
use manager\ObjectifManager;
use manager\SecteurManager;
use src\Entreprise;
use src\NombreEmploye;
use src\Objectif;
use manager\EntrepriseManager;
use src\Secteur;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$entrepriseId = Functions::getValueChamp($_POST["id"]);
$entrepriseNom = Functions::getValueChamp($_POST["entreprise"]);
$nombreEmployeId = Functions::getValueChamp($_POST["nbEmployes"]);
$objectifId = Functions::getValueChamp($_POST["objectif"]);
$secteurId = Functions::getValueChamp($_POST["secteur"]);


$erreurs = array();

$entrepriseManager = new EntrepriseManager();
$nombreEmployeManager = new NombreEmployeManager();
$objectifManager = new ObjectifManager();
$secteurManager = new SecteurManager();

if(intval($entrepriseId) == 0){
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."
    ));
    exit();
}


try{
    Functions::validEntrepriseNewName($entrepriseNom, intval($entrepriseId), $entrepriseManager);
} catch(Exception $e){
    $erreurs["entreprise"] = $e -> getMessage();
}

if(!$nombreEmployeManager -> isExist(intval($nombreEmployeId))){
    $erreurs["nbEmployes"] = "Le nombre d'employés selectionné est incorrect.";
}


if(!$objectifManager -> isExist(intval($objectifId))){
    $erreurs["objectif"] = "L'objectif selectionné est incorrect.";
}


if(!$secteurManager -> isExist(intval($secteurId))){
    $erreurs["secteur"] = "Le secteur d'activité selectionné est incorrect.";
}

if(empty($erreurs)){
    
    $entreprise = new Entreprise();
    $nombreEmploye = new NombreEmploye();
    $objectif = new Objectif();
    $secteur = new Secteur();
    
    $nombreEmploye -> setId(intval($nombreEmployeId));
    
    $objectif -> setId(intval($objectifId));
    
    $secteur -> setId(intval($secteurId));
    
    $entreprise->setId(intval($entrepriseId));
    $entreprise -> setNom(ucfirst($entrepriseNom));
    $entreprise -> setNombreEmploye($nombreEmploye);
    $entreprise -> setObjectif($objectif);
    $entreprise -> setSecteur($secteur);
    
    if($entrepriseManager->updateEntrepriseInfos($entreprise)){
        $_SESSION["utilisateur"]->setEntreprise($entreprise);
         echo json_encode(array(
         	"type" => "success"
        	));
    }
    
}else{
    echo json_encode($erreurs);
}

