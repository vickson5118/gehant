<?php

namespace validation\entreprise;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ContactManager.php");

use Exception;
use utils\Functions;
use manager\ContactManager;
use src\Contact;
use DateTime;
use utils\Constants;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
     echo json_encode(array(
     	"type" => "session"
    	));
     exit();
}

$objet = Functions::getValueChamp($_POST["objet"]);
$message = Functions::getValueChamp($_POST["message"]);

$erreurs = array();

try {
    Functions::validTexte($objet, "objet", 15, 150, true);
} catch (Exception $e) {
    $erreurs["objet"] = $e->getMessage();
}

try {
    Functions::validTexte($message, "message", 20, 1500, true);
} catch (Exception $e) {
    $erreurs["message"] = $e->getMessage();
}

if(empty($erreurs)){
    
    try {
        $contactManager = new ContactManager();
        $contact = new Contact();
        
        $dateEnvoi = new DateTime();
        $dateEnvoi = $dateEnvoi->format("Y-m-d");
        
        $contact->setObjet(ucfirst($objet));
        $contact->setMessage(ucfirst($message));
        $contact->setUtilisateur($_SESSION["utilisateur"]);
        $contact->setDateEnvoi($dateEnvoi);
        
        if(Functions::sendMail(Constants::SMTP_USERNAME, ucfirst($objet), ucfirst($message), ucfirst($message)) && 
            $contactManager->addEntrepriseContact($contact)){
            echo json_encode(array(
                "type" => "success"
            ));
        }else{
            $erreurs["email"] = "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement.";
            echo json_encode($erreurs);
            exit();
        }
        
    }catch (Exception $e){
        $erreurs["email"] = $e->getMessage();
        echo json_encode($erreurs);
    }
    
}else{
    echo json_encode($erreurs);
}
