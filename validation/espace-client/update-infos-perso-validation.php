<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Pays.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PaysManager.php");

session_start();

use manager\UtilisateurManager;
use utils\Functions;
use manager\PaysManager;
use src\Pays;

if (($_SESSION ["utilisateur"]) == null) {
    echo json_encode(array(
        "type" => "session"
    ));
    exit();
}

$nom = strip_tags(trim($_POST["nom"]));
$prenoms = strip_tags(trim($_POST["prenoms"]));
$telephone = strip_tags(trim($_POST["telephone"]));
$naissance = strip_tags(trim($_POST["naissance"]));
$pays_id = strip_tags(trim($_POST["pays"]));
$fonction = strip_tags(trim($_POST["fonction"]));

$erreurs = array();
$utilisateurManager = new UtilisateurManager();
$paysManager = new PaysManager();

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
    Functions::validTelephone($telephone);
} catch(Exception $e){
    $erreurs["telephone"] = $e -> getMessage();
}

try{
    Functions::validDate($naissance);
} catch(Exception $e){
    $erreurs["naissance"] = $e -> getMessage();
}

try{
    Functions::validPays($pays_id, $paysManager);
} catch(Exception $e){
    $erreurs["pays"] = $e -> getMessage();
}

try{
    Functions::validTexte($fonction, "fonction", 5, 30, false);
} catch(Exception $e){
    $erreurs["fonction"] = $e -> getMessage();
}

if(empty($erreurs)){
    $utilisateur = $_SESSION["utilisateur"];
    $pays = new Pays();
    
    $pays->setId(intval($pays_id));
    
    $utilisateur->setNom(ucfirst(strtolower($nom)));
    $utilisateur -> setPrenoms(ucwords(strtolower($prenoms)));
    $utilisateur->setTelephone($telephone);
    $utilisateur->setPays($pays);
    $utilisateur->setFonction(ucfirst($fonction));
    if($naissance != null){
        $utilisateur->setDateNaissance((DateTime::createFromFormat("d/m/Y", $naissance))->format("Y-m-d"));
    }else{
        $utilisateur->setDateNaissance(null);
    }
       
    
    
    
    if( $utilisateurManager->updateInfosPersos($utilisateur)){
        //changer le format dans l'objet
        $utilisateur->setDateNaissance($naissance);
        echo json_encode(array(
            "type" => "success"
        ));
    }
    
   
    
}else{
    echo json_encode($erreurs);
}



