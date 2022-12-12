<?php

namespace validation\compte;

require_once ($_SERVER ["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");

use manager\UtilisateurManager;

$token = $_GET ["token"];

$utilisateurManager = new UtilisateurManager ();

$utilisateur = $utilisateurManager->connexion( $token, true);

if ($utilisateur != NULL) {

	$utilisateurManager->updateTokenNullAndActiveCompte ( $utilisateur->getId () );

	session_start ();
	$_SESSION ["utilisateur"] = $utilisateur;

	header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
} else {
	header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
	exit ();
}