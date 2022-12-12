<?php 



require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/DomaineManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/PaysManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/EntrepriseManager.php");

session_start (); 

use manager\UtilisateurManager;
use manager\DomaineManager;
use manager\FormationManager;
use manager\PaysManager;
use manager\EntrepriseManager;
use utils\Functions;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$utilisateurManager = new UtilisateurManager();
$domaineManager = new DomaineManager();
$formationManager = new FormationManager();
$paysManager = new PaysManager();
$entrepriseManager = new EntrepriseManager();

$nombreAdmin = $utilisateurManager->getNombreUser(true);
$nombreUser = $utilisateurManager->getNombreUser(false);
$nombreDomaine = $domaineManager->getNombreDomaine();
$nombreFormation = $formationManager->getNombreFormation();
$nombrePays = $paysManager->getNombrePays();
$nombreEntreprise = $entrepriseManager->getNombreEntreprise();

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Tableau de bord  | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/w1-admin/dashboard.css" type="text/css" />
	</head>
	
	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
    		<div class="container-fluid">
    
    			<div class="admin-infos-container">
    				<i class='bx bxs-user-circle'></i>
    				<div class="admin-connect">
    						<span><?= $nombreAdmin ?> </span> <span><a href="/w1-admin/administrateurs"> 
    						<?php if($nombreAdmin <= 1){ echo "Administrateur"; }else{ echo "Administrateurs" ;}?></a></span>
    					</div>
    					
    			</div>
    
    			<div class="user-infos-container">
    				 <i class="bi bi-people-fill"></i>
    					<div class="user-connect">
    						<span><?= $nombreUser ?> </span> <span><a href="/w1-admin/utilisateurs">
    						<?php if($nombreUser <= 1){ echo "Utilisateur"; }else{ echo "Utilisateurs" ;}?></a></span>
    					</div> 
    					
    			</div>
    			
    			<div class="domaines-formations-infos-container">
    				 <i class="bi bi-journals"></i>
    					<div class="domaines-formations-connect">
    						<span><?= $nombreDomaine ?> </span> <span><a href="/w1-admin/domaines">
    						<?php if($nombreDomaine <= 1){ echo "Domaine"; }else{ echo "Domaines" ;}?>
    						</a></span>
    					</div>
    				
    			</div>
    
    			<div class="formations-infos-container">
    				 <i class="bi bi-book-fill"></i>
    					<div class="formations-connect">
    						<span><?= $nombreFormation ?> </span> <span><a href="/w1-admin/formations">
    						<?php if($nombreFormation <= 1){ echo "Formation"; }else{ echo "Formations" ;}?></a></span>
    					</div>
    			</div>
    			
    			
    			<div class="pays-infos-container">
    				 <i class="bi bi-flag-fill"></i>
    					<div class="pays-add">
    						<span><?= $nombrePays ?> </span> <span><a href="/w1-admin/pays">Pays</a></span>
    					</div>
    			</div>
    			
    			<div class="temoignages-infos-container">
    				 	<i class="bi bi-blockquote-left"></i>
    					<div class="temoignage-add">
    						<span><?= $nombreEntreprise ?> </span> <span><a href="/w1-admin/entreprises">
    						<?php if($nombreEntreprise <= 1){ echo "Entreprise"; }else{ echo "Entreprises" ;}?></a></span>
    					</div>
    			</div>
    			
    		</div>
    	</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	</body>
	
</html>