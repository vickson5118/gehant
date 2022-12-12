<?php
namespace validation\compte;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");

use utils\Functions;
use manager\AchatManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
     echo json_encode(array(
     	"type" => "session"
    	));
     exit();
}

$panierId = Functions::getValueChamp($_POST["panierId"]);

if(intval($panierId) == null){
     echo json_encode(array(
     	"msg" => "Une erreur est survenue. veuillez réessayer ultérieurement."
    	));
}

$achatManager = new AchatManager();

if($achatManager->deleteAchat(intval($panierId))){
     echo json_encode(array(
     	"type" => "success"
    	));
}