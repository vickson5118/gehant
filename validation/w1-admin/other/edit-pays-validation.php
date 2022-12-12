<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PaysManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Pays.php");

use utils\Functions;
use src\Pays;
use manager\PaysManager;

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$nom = Functions::getValueChamp($_POST["nom"]);
$paysId = Functions::getValueChamp($_POST["id"]);

$paysManager = new PaysManager();
$erreurs = array();

if(intval($paysId) == 0){
    $erreurs["id"] = "Une erreur est survenue. Veuillez réessayer ultérieurement.";
}

try {
    if($paysManager->paysExistNom($nom)){
        $erreurs["nom"] = "Le nom du pays existe déja. Merci d'enrégsitrer un autre pays.";
    }
    Functions::validTexte($nom, "nom du pays", 3, 30, true);
} catch (Exception $e) {
    $erreurs["nom"] = $e->getMessage();
}

if(empty($erreurs)){
   
    $pays = new Pays();
    
    $pays->setId(intval($paysId));
    $pays->setNom(ucfirst($nom));
    
    if($paysManager->editPays($pays)){
         echo json_encode(array(
         	"type" => "success"
        	));
    }
    
}else{
    
    echo json_encode($erreurs);
    
}

