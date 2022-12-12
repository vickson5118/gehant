<?php

namespace validation\formation;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FactureManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Facture.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/pdf/html2pdf/src/Html2Pdf.php");

use utils\Functions;
use manager\FormationManager;
use manager\AchatManager;
use src\Achat;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
    	"type" => "session"
   	));
    exit ();
}

$formationId = Functions::getValueChamp($_POST["id"]);

if($formationId == null || intval($formationId) == 0){
     echo json_encode(array(
     	"msg" => "Une erreur est survenue, veuillez réessayer ultérieurement."
    	));
     exit();
}

$utilisateur = $_SESSION ["utilisateur"];
$formationManager = new FormationManager();
$formation = $formationManager->getOneFormationInfoById(intval($formationId));

if($formation != null){
    
    $achatManager = new AchatManager();
    $achat = new Achat();
    
    $achat->setFormation($formation);
    $achat->setUtilisateur($utilisateur);
    
    if($achatManager->addFormationParticulier($achat)){
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
}

