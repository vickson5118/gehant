<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

session_start();

use utils\Constants;
use utils\Functions;
use manager\UtilisateurManager;

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}


$email = Functions::getValueChamp($_POST["email"]);
$newEmail = Functions::getValueChamp($_POST["newEmail"]);

$erreurs = array();
$utilisateurManager = new UtilisateurManager();


try {
    if($email == null){
        throw new Exception("Le champ ancienne adresse E-mail ne peut être vide.");
    }
} catch (Exception $e) {
    $erreurs["email"] = $e->getMessage();
}

try {
    Functions::validEmail($newEmail, $utilisateurManager);
} catch (Exception $e) {
   $erreurs["newEmail"] = $e->getMessage();
}


if(empty($erreurs) && $utilisateurManager->emailExist($email)){
    
    $utilisateur = $_SESSION["utilisateur"];
    
    // Mot de passe total généré
    $passwordGenerated = Functions::generatePassword(Constants::PASSWORD_LENGTH);
    
    $passwordGeneratedAndSalt = Constants::PASSWORD_GENERATE_START_SALT . $passwordGenerated . Constants::PASSWORD_GENERATE_END_SALT;
    
    $passwordCrypte = password_hash($passwordGeneratedAndSalt, PASSWORD_DEFAULT);
    
    $utilisateur -> setPassword($passwordCrypte);
    $utilisateur -> setEmail($newEmail);
    
    $objet = "Mise à jour de votre adresse E-mail";
    
    $message = "<h3 style='text-align:center'>Bonjour " . $utilisateur->getPrenoms(). "</h3><p style='text-align:justify;'>Vous avez
                        procédé à la modification de votre adresse E-mail.<br />
                    Vous trouverez ci-dessous vos nouveaux identifiants de connexion</p>" . "<p style='margin-bottom:30px;'>Email : "
                        . $newEmail . "<br /> Mot de passe : " . $passwordGenerated . "</p>
                    <p>A bientôt,<br />L'équipe Gehant</p>";
                        
                        $altMessage = "Bonjour " . $utilisateur->getPrenoms() . ",\n\nVous avez procédé à la modification de votre adresse E-mail.\n
                Vous trouverez ci-dessous vos nouveaux identifiants de connexion\n" . "Email : " . $email . "\nMot de passe : " .$passwordGenerated . "\n.\n A bientôt,L'équipe Gehant";


    try {
        if (Functions::sendMail($newEmail, $objet, $message, $altMessage)) {
            if ($utilisateurManager->updateEmail($utilisateur)) {
                session_destroy();
                echo json_encode(array("type" => "success"));
            }
        } else {
            echo json_encode(array("mail" => "Une erreur est survénue. Veuillez verifier votre connexion et réessayer ultérieurement."));
            exit();
        }
    } catch(Exception $e) {
        echo json_encode(array("mail" => $e->getMessage()));
        exit();
    }


}elseif (empty($erreurs) && !$utilisateurManager->emailExist($email)){
    echo json_encode(array(
        "email" => "L'ancienne adresse E-mail n'existe pas."
    ));
    exit();
}else{
    echo json_encode($erreurs);
    
}



