<?php

namespace validation\compte;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");

use Exception;
use manager\AchatManager;
use utils\Functions;
use utils\Constants;
use DateTime;

session_start();
if(($_SESSION["utilisateur"]) == null){
    echo json_encode(array(
        "type" => "session"));
    exit();
}

$utilisateur = $_SESSION["utilisateur"];
$achatManager = new AchatManager();

$dateInscription = new DateTime();
$dateInscription = $dateInscription -> format("Y/m/d");

if($achatManager -> updatePaid($utilisateur -> getId(), $dateInscription, false)){
    $objet = "Demande de formation";
    $message = "<h3 style='text-align:center'>Bonjour,</h3>
                        <p style='text-align:justify;'>Une demande de formation vient d’être créée. Pour la consulter, rendez-vous à l'adresse suivante</p>
                        <p style='text-align:center;margin-bottom:30px;'>
                            <a href='http://" . $_SERVER["HTTP_HOST"] .
        "/w1-admin' style='text-decoration:none; padding:15px; color:#fff; background:#f07b16;'>Consulter la demande</a>
                       </p>
                       <p>Si vous ne parvenez pas à vous connecter en cliquant sur le bouton, copiez puis collez le lien suivant dans votre navigateur.</p>
                       <p>http://" . $_SERVER["HTTP_HOST"] . "/w1-admin</p>
                       <p>A bientôt,<br />L'équipe GEHANT</p>";
    $altMessage = "Bonjour,\n\nVotre mot de passe a été redéfinir avec succès.\n
                        Une demande de formation vient d’être créée. Pour la consulter, rendez-vous à l'adresse suivanter.\n http://" . $_SERVER["HTTP_HOST"] . "/w1-admin .\nA bientôt,L'équipe GEHANT";

    try {
        if(Functions::sendMail(Constants::SERVICE_CLIENT_EMAIL, $objet, $message, $altMessage)){
            echo json_encode(array(
                "type" => "success"));
        }else{
            echo json_encode(array("mail" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."));
            exit();
        }

    } catch(Exception $e) {
        echo json_encode(array(
            "msg" => $e->getMessage()));
        exit();
    }

} else{
    echo json_encode(array(
        "msg" => "Une erreur est survenue. Veuillez réessayer ultérieurement."));
}
