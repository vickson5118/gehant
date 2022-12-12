<?php

namespace validation\compte;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");


use DateTime;
use Exception;
use manager\UtilisateurManager;
use src\Utilisateur;
use utils\Constants;
use utils\Functions;
use src\TypeCompte;
use src\Entreprise;

$nom = Functions::getValueChamp($_POST["nom"]);
$prenoms = Functions::getValueChamp($_POST["prenoms"]);
$email = Functions::getValueChamp($_POST["email"]);

$erreurs = array();
$utilisateurManager = new UtilisateurManager();

try{
    Functions::validTexte($nom, "nom", 2, 15, true);
} catch(Exception $e){
    $erreurs["nom"] = $e -> getMessage();
}


try{
    Functions::validTexte($prenoms, "prenoms", 2, 30, true);
} catch(Exception $e){
    $erreurs["prenoms"] = $e -> getMessage();
}


try{
    Functions::validEmail($email, $utilisateurManager);
} catch(Exception $e){
    $erreurs["email"] = $e -> getMessage();
}



if(empty($erreurs)){
    
    // Mot de passe total généré
    $passwordGenerated = Functions::generatePassword(Constants::PASSWORD_LENGTH);
    $passwordGeneratedAndSalt = Constants::PASSWORD_GENERATE_START_SALT . $passwordGenerated . Constants::PASSWORD_GENERATE_END_SALT;
    $passwordCrypte = password_hash($passwordGeneratedAndSalt, PASSWORD_DEFAULT);
    
    // Generer un token
    $token = md5($email);
    
    // formater les mots
    $nom = ucfirst(strtolower($nom));
    $prenoms = ucwords(strtolower($prenoms));
    
    //Envoyer le mail
    $objet = "Création d'un compte GEHANT";
    $message = "<h3 style='text-align:center'>Bonjour " . $prenoms . "</h3>
                        <p style='text-align:justify;'>Vous y êtes.<br />Plus qu'une dernière étape et vous pourrez bénéficier de vos formations<br />Vous trouverez ci-dessous vos identifiants de connexion à votre compte entreprise GEHANT</p>
                        <p style='margin-bottom:30px;'>Email : " . $email . "<br /> Mot de passe : " . $passwordGenerated . "</p>
                        <p style='text-align:center;margin-bottom:30px;'>
                            <a href='http://" . $_SERVER["HTTP_HOST"] . "/activation/" . $token . "' style='text-decoration:none; padding:15px; color:#fff; background:#f07b16;'>Activez votre compte maintenant</a>
                       </p>
                       <p>Si vous ne parvenez pas à activer en cliquant sur le bouton, copiez puis collez le lien suivant dans votre navigateur.</p>
                       <p>http://". $_SERVER["HTTP_HOST"] . "/activation/" . $token . "</p>
                       <p style='text-align:justify'>Activez votre compte et apprenez en toute simplicité.</p>
                       <p>A bientôt,<br />L'équipe GEHANT</p>";
    
                    $altMessage = "Bonjour " . $prenoms . ",\n\nVous y êtes. Plus qu'une dernière étape et vous pourrez bénéficier de vos
                    formations.\n" . "Vous trouverez ci-dessous vos identifiants de connexion\nEmail : " . $email . "\nMot de passe : " .
                    $passwordGenerated . "\n" . "Copiez-collez le lien suivant dans votre navigateur.\n http://"
                        . $_SERVER["HTTP_HOST"] . "/activation/" . $token . "pour terminer votre inscription.\n
                        Activez votre compte et apprenez en toute simplicité.\n " . "A bientôt,L'équipe GEHANT";

    try {
        if (Functions::sendMail($email, $objet, $message, $altMessage)) {

            $dateInscription = new DateTime();
            $dateInscription = $dateInscription->format("Y/m/d");

            $utilisateur = new Utilisateur();
            $typeCompte = new TypeCompte();
            $entreprise = new Entreprise();

            $typeCompte->setId(Constants::COMPTE_STANDARD);

            $entreprise->setId(null);
            $utilisateur->setNom($nom);
            $utilisateur->setPrenoms($prenoms);
            $utilisateur->setEmail($email);
            $utilisateur->setPassword($passwordCrypte);
            $utilisateur->setTypeCompte($typeCompte);
            $utilisateur->setToken($token);
            $utilisateur->setActive(false);
            $utilisateur->setTelephone(null);
            $utilisateur->setEntreprise($entreprise);
            $utilisateur->setDateInscription($dateInscription);

            $utilisateurManager->inscription($utilisateur, false);

            echo json_encode(array("type" => "success"));

        } else {
            echo json_encode(array("mail" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."));
            exit();
        }
    } catch(Exception $e) {
        echo json_encode(array("mail" => $e->getMessage()));
        exit();
    }

} else{
    
    echo json_encode($erreurs);
    
}



