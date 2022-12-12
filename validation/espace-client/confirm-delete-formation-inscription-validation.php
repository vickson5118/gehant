<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");

use utils\Functions;
use manager\AchatManager;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$id = Functions::getValueChamp($_POST["id"]);

if(intval($id) == 0){
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
    exit();
}

$achtaManager = new AchatManager();

if($achtaManager->deleteAchat(intval($id))){
    echo json_encode(array(
        "type" => "success"
    ));
}