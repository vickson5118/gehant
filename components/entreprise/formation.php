<?php

namespace components\entreprise;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Formation.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");

use manager\AchatManager;
use utils\Functions;

session_start();

Functions::redirectWhenNotConnexionEntreprise($_SESSION["utilisateur"]);

$achatManager = new AchatManager();
$listeFormationCommande = $achatManager -> getListeFormationNotConfirmPaid($_SESSION["utilisateur"] -> getEntreprise() 
    -> getId(), true);
$listeFormationPaid = $achatManager -> getListeFormationConfirmPaid($_SESSION["utilisateur"] -> getEntreprise() 
    -> getId(), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Formations achetées | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/entreprise/formation.css"
	type="text/css" />
</head>

<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>

		<div class="container" style="margin-top: 100px;">
				
				<?php if(empty($listeFormationCommande) && empty($listeFormationPaid)){ ?>
				<div class="justify-content-center">
    				<div class="panier-icon">
    					<i class="bi bi-cart-fill"></i>
    				</div>
    				<p class="text-panier-vide">Vous n'avez encore effectué aucun achat
    					de formation</p>
    			</div>
				<?php } else{ ?>
            		<?php if(!empty($listeFormationCommande)){ ?>
    		   	<div class="formation-not-paid-container">
    		   		<p class="paiement-header">Les formations en attente de paiement</p>
    		   		<div class="row">
            	        <?php foreach ($listeFormationCommande as $achat){ ?>
                		
                			<div class="col-md-3 formation-item">
                			
                    			<div class="card">
                    				<img src="<?= $achat->getFormation()->getIllustration() ?>" class="card-img-top" alt='<?= $achat->getFormation()->getTitre() ?>'>
                    				<div class="card-body">
                    						
                    					<h1 class="card-title">
                    						
                    						<a href="/espace-client/entreprise/formations/<?= $achat->getFormation()->getDomaine()->getTitreUrl() ?>/<?= $achat->getFormation()->getTitreUrl() ?>">
                    							<span class="cours-title"><?= $achat->getFormation()->getTitre() ?></span>
                    						</a>
                    					</h1>
                    					<div class="auteur">
                    						<span><?= $achat->getFormation()->getAuteur()->getPrenoms()." ".$achat->getFormation()->getAuteur()->getNom() ?></span>
                    					</div>
                    					
                    				</div>
                    			</div>
                    		</div>
                		
            		<?php }?>
            		</div>
    		   </div>
		   <?php } ?>
		   
		   <?php if(!empty($listeFormationPaid)){ ?>
    		   <div class="formation-paid-container">
    		   		<p class="paiement-header">Mes formations</p>
            	       <div class="row">
            	       	 <?php foreach ($listeFormationPaid as $achat){ ?>
                		<div class="col-md-3 formation-item">
                			
                			<div class="card">
                				<img src="<?= $achat->getFormation()->getIllustration() ?>" class="card-img-top" alt='<?= $achat->getFormation()->getTitre() ?>'>
                				<div class="card-body">
                						
                					<h1 class="card-title">
                						<a href="/espace-client/entreprise/formations/<?= $achat->getFormation()->getDomaine()->getTitreUrl() ?>/<?= $achat->getFormation()->getTitreUrl() ?>">
                							<span class="cours-title"><?= $achat->getFormation()->getTitre() ?></span>
                						</a>
                					</h1>
                					<div class="auteur">
                						<span><?= $achat->getFormation()->getAuteur()->getPrenoms()." ".$achat->getFormation()->getAuteur()->getNom() ?></span>
                					</div>
                					
                				</div>
                			</div>
                		</div>
        		<?php }?>
            	       </div>
		  	</div>
		<?php } } ?>
            	
			</div>
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
	</body>

</html>