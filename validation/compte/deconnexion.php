<?php

namespace validation\compte;

session_start();

require_once ($_SERVER ["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");

use manager\UtilisateurManager;

$id = intval($_GET ["id"]);

$utilisateurManager = new UtilisateurManager ();

if ($utilisateurManager -> deconnexion( $id )) {
    session_destroy();
}
header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );