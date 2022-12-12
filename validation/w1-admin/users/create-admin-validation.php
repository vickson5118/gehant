<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/src/TypeCompte.php");
require_once ( $_SERVER["DOCUMENT_ROOT"] . "/manager/UtilisateurManager.php");

use utils\Constants;
use utils\Functions;
use manager\UtilisateurManager;
use src\Utilisateur;
use src\TypeCompte;
use src\Entreprise;

session_start();

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$nom = Functions::getValueChamp($_POST["nom"]);
$prenoms = Functions::getValueChamp($_POST["prenoms"]);
$email = Functions::getValueChamp($_POST["email"]);

$erreurs = array();
$utilisateurManager = new UtilisateurManager();

try{
    Functions::validTexte($nom, "nom", 2, 30, true);
} catch(Exception $e){
    $erreurs["nom"] = $e -> getMessage();
}

try{
    Functions::validTexte($prenoms, "prenoms", 2, 150, true);
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
    $passwordGenerated = Functions::generatePassword(Constants::PASSWORD_ADMIN_LENGTH);
    
    $passwordGeneratedAndSalt = Constants::PASSWORD_GENERATE_START_SALT . $passwordGenerated . Constants::PASSWORD_GENERATE_END_SALT;
    
    $passwordCrypte = password_hash($passwordGeneratedAndSalt, PASSWORD_DEFAULT);
    
    // Generer un token
    //$token = md5($email);
    
    //formater les mots
    $nom = ucfirst(strtolower($nom));
    $prenoms = ucwords(strtolower($prenoms));
    
    $objet = "Création d'un compte GEHANT";
    $message = "<h3 style='text-align:center'>Bonjour " . $prenoms. "</h3><p style='text-align:justify;'>Vous avez été 
                enrégistré en tant qu'administrateur de GEHANT.<br />Vous trouverez ci-dessous vos identifiants de connexion</p>
                    <p style='margin-bottom:30px;'>Email : " . $email . "<br /> Mot de passe : " . $passwordGenerated . "</p>
                     <p>A bientôt,<br />L'équipe GEHANT</p>";
                            
    $altMessage = "Bonjour " . $prenoms . ",\n\nVous avez été enrégistré en tant que administrateur de GEHANT.
                     \nVous trouverez ci-dessous vos identifiants de connexion\n" . "Email : " . $email . "\nMot de passe : " .
                    $passwordGenerated . "\n\n " . "A bientôt,L'équipe GEHANT";
    
    try {
        
        //envoyer le mail
        if(Functions::sendMail($email, $objet, $message, $altMessage)){
            
            $dateInscription = new DateTime();
            $dateInscription = $dateInscription -> format("Y-m-d");
            
            $utilisateur = new Utilisateur();
            $typeCompte = new TypeCompte();
            $entreprise = new Entreprise();
            
            $typeCompte->setId(Constants::COMPTE_ADMIN);
            
            $entreprise->setId(null);
            
            $utilisateur -> setNom($nom);
            $utilisateur -> setPrenoms($prenoms);
            $utilisateur -> setEmail($email);
            $utilisateur -> setPassword($passwordCrypte);
            $utilisateur->setTypeCompte($typeCompte);
            $utilisateur -> setToken(null);
            $utilisateur->setTelephone(null);
            $utilisateur->setActive(true);
            $utilisateur->setEntreprise($entreprise);
            $utilisateur -> setDateInscription($dateInscription);
            
            
            $utilisateurManager -> inscription($utilisateur,true);
            
            echo json_encode(array(
                "type" => "success"
            ));
            
        }else{
            
            echo json_encode(array(
                "mail" => "Une erreur est survenue lors de l'envoi du mail. Vérifier votre connexion internet et réessayer ultérieurement."
            ));
            
        }
        
    } catch (Exception $e) {
        $erreurs["mail"] = $e->getMessage();
        echo json_encode($erreurs);
    }
    
}else{
    echo json_encode($erreurs);
}


