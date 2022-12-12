<?php

namespace validation\compte;

require_once($_SERVER["DOCUMENT_ROOT"]."/utils/Functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

use Exception;
use utils\Constants;
use utils\Functions;
use manager\UtilisateurManager;

$email = Functions::getValueChamp($_POST["email"]);

$utilisateurManager = new UtilisateurManager();

$utilisateur = $utilisateurManager->userEmailExist($email);

if($utilisateur != null){
    
    // Mot de passe total généré
    $passwordGenerated = Functions::generatePassword(Constants::PASSWORD_LENGTH);
    $passwordGeneratedAndSalt = Constants::PASSWORD_GENERATE_START_SALT . $passwordGenerated . Constants::PASSWORD_GENERATE_END_SALT;
    $passwordCrypte = password_hash($passwordGeneratedAndSalt, PASSWORD_DEFAULT);
    
    //Envoyer le mail
    $objet = "Réinitialisation du mot de passe GEHANT";
    $message = "<h3 style='text-align:center'>Bonjour " . $utilisateur->getPrenoms() . "</h3>
                        <p style='text-align:justify;'>Vos accès ont été réinitialisés avec succès.<br />Trouvez ci-dessous le nouveau mot de passe de votre compte GEHANT</p>
                        <p style='margin-bottom:30px;'>Email : " . $email . "<br /> Mot de passe : " . $passwordGenerated . "</p>
                        <p style='text-align:center;margin-bottom:30px;'>
                            <a href='http://" . $_SERVER["HTTP_HOST"] . "/compte/connexion' style='text-decoration:none; padding:15px; color:#fff; background:#f07b16;'>Connectez-vous</a>
                       </p>
                       <p>Si vous ne parvenez pas à vous connecter en cliquant sur le bouton, copiez puis collez le lien suivant dans votre navigateur.</p>
                       <p>http://". $_SERVER["HTTP_HOST"] . "/compte/connexion</p>
                       <p style='text-align:justify'>Connectez-vous et apprenez en toute simplicité.</p>
                       <p>A bientôt,<br />L'équipe GEHANT</p>";
    
    $altMessage = "Bonjour " . $utilisateur->getPrenoms() . ",\n\nVotre mot de passe a été redéfinir avec succès.\n
                        Vous trouverez ci-dessous votre nouveau mot de passe GEHANT\nEmail : " . $email . "\nMot de passe : " .
                    $passwordGenerated . "\n" . "Copiez-collez le lien suivant dans votre navigateur.\n http://"
                        . $_SERVER["HTTP_HOST"] . "/compte/connexion pour vous connecter.\n
                        Connectez-vous et apprenez en toute simplicité.\n " . "A bientôt,L'équipe GEHANT";

    try {
        if (Functions::sendMail($email, $objet, $message, $altMessage)) {

            $utilisateurManager->updatePassword($passwordCrypte, $utilisateur->getId());

            echo json_encode(array("type" => "success"));

        } else {
            echo json_encode(array("msg" => "Une erreur est survenue lors de l'envoi de mail. Merci de verifier la connexion internet et de réessayer ultérieuremet."));
            exit();
        }
    } catch(Exception $e) {
        echo json_encode(array("msg" => $e->getMessage()));
        exit();
    }


}else{
    echo json_encode(array(
        "msg" => "Le compte associé à cette adresse E-mail est inexistant. 
            Merci de rentrer une adresse E-mail valide ou de créer un compte GEHANT."
    ));
}