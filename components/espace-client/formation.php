<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");

session_start();

use manager\AchatManager;

$achatManager  = new AchatManager();
$listeFormationCommande = $achatManager->getListeFormationNotConfirmPaid($_SESSION["utilisateur"]->getId(),false);
$listeFormationPaid = $achatManager->getListeFormationConfirmPaid($_SESSION["utilisateur"]->getId(),false);

?>

<div class="container">
	
		<?php if(empty($listeFormationCommande) && empty($listeFormationPaid)){ ?>
		<div class="justify-content-center">
			<div class="historique-icon"><i class="bi bi-receipt"></i></div>
			<p class="text-historique-vide">Vous n'avez encore effectué aucun achat de formation.</p>
		</div>
		<?php }else{ ?>
		   
		   <?php if(!empty($listeFormationCommande)){ ?>
    		   	<div class="formation-not-paid-container">
    		   		<p class="paiement-header">Les formations en attente de paiement</p>
    		   		<div class="row">
            	        <?php foreach ($listeFormationCommande as $achat){ ?>
                		
                			<div class="col-md-3 formation-item">
                			
                    			<div class="card">
                    				<?php if(!$achat->isPaidForced()){?>
                    					<button id="btn-delete-formation-inscription" data-bs-toggle="modal" data-bs-target="#staticBackdropAnnulerInscription" type="button" value="<?= $achat->getId() ?>" title="Annuler mon inscription à la formation"><i class="bi bi-x-square-fill"></i></button>
                    				<?php }?>
                    				<img src="<?= $achat ->getFormation() ->getIllustration() ?>" class="card-img-top" alt='<?= $achat ->getFormation()->getTitre() ?>'>
                    				<div class="card-body">
                    					
                    					<h1 class="card-title">
                    						
                    						<a href="/formations/<?= $achat ->getFormation()->getDomaine()->getTitreUrl() ?>/<?= $achat ->getFormation()->getTitreUrl() ?>">
                    							<span class="cours-title"><?= $achat ->getFormation()->getTitre() ?></span>
                    						</a>
                    					</h1>
                    					<div class="auteur">
                    						<span><?= $achat ->getFormation()->getAuteur()->getPrenoms()." ".$achat ->getFormation()->getAuteur()->getNom() ?></span>
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
                				<img src="<?=  $achat ->getFormation()->getIllustration() ?>" class="card-img-top" alt='<?=  $achat ->getFormation()->getTitre() ?>'>
                				<div class="card-body">
                						
                					<h1 class="card-title">
                						<a href="/formations/<?=  $achat ->getFormation()->getDomaine()->getTitreUrl() ?>/<?=  $achat ->getFormation()->getTitreUrl() ?>">
                							<span class="cours-title"><?=  $achat ->getFormation()->getTitre() ?></span>
                						</a>
                					</h1>
                					<div class="auteur">
                						<span><?=  $achat ->getFormation()->getAuteur()->getPrenoms()." ". $achat ->getFormation()->getAuteur()->getNom() ?></span>
                					</div>
                					
                				</div>
                			</div>
                		</div>
        		<?php }?>
            	       </div>
		  	</div>
		<?php } } ?>
	
</div>
	<?php
	require_once("modal/confirm-delete-formation-inscription.php");
      ?>
      <script src="/inc/js/espace-client/formation.js" type="text/javascript"></script>
