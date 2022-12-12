<?php


namespace components\compte;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/FormationManager.php");

session_start();

use utils\Functions;
use utils\Constants;
use manager\AchatManager;
use manager\FormationManager;


if (($_SESSION ["utilisateur"]) != null) {
    $achatManager = new AchatManager();
    $formationManager = new FormationManager();
    
    $listeFormation = $formationManager->getTwelveLastFormation();
    
    if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){
        $listeFormationNotBuy = $achatManager->getListeFormationNotPaid($_SESSION["utilisateur"]->getEntreprise()->getId());
    }else {
        $listeFormationNotBuy = $achatManager->getUserListeFormationNotPaid($_SESSION["utilisateur"]->getId());
    }
    
}else{
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/compte/connexion" );
    exit ();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Panier | Gehant</title>
<meta charset="utf-8" />
		<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/compte/panier.css" type="text/css" />
</head>
<body>

	<!-- Header -->
	<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/header.php"); ?>

	<div class="container-fluid" id="panier-container">

		<div class="container">
			<?php if (empty($listeFormationNotBuy)){ ?>
			
			<div class="row justify-content-center">
				<div class="col-md-8">

					<div class="panier-icon">
						<i class="bi bi-cart-fill"></i>
					</div>
					<p class="text-panier-vide">Votre panier est vide</p>

				</div>
				</div>
				
				<?php }else{ ?>
				<div class="row">
				 <?php
				$prixTotal = 0;
				foreach ($listeFormationNotBuy as $achat){
				    $prixTotal += $achat->getFormation()->getPrix();
				    ?>
				    
					<div class="col-md-9 one-formation-panier-container">

						<div class="one-formation-panier">
							<div class="formation-infos">
								<img src="<?= $achat->getFormation()->getIllustration() ?>" alt="<?= $achat->getFormation()->getTitre() ?>" />
								
								<div class="formation-infos-content">
									<h1 class="formation-title">
										<a href="/formations/<?= $achat->getFormation()->getDomaine()->getTitreUrl() ?>/<?= $achat->getFormation()->getTitreUrl() ?>"><?= $achat->getFormation()->getTitre() ?></a>
									</h1>
									<p class="formateur-reference">Par <?= $achat->getFormation()->getAuteur()->getPrenoms()." ".$achat->getFormation()->getAuteur()->getNom() ?></p>
									<p class="date-formation">Date : <?= Functions::formatFormationDate(Functions::convertDateEnToFr($achat->getFormation()->getDateDebut()), Functions::convertDateEnToFr($achat->getFormation()->getDateFin())) ?></p>
									<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>
    									<p class="entreprise-name">Entreprise: <b><?= $achat->getEntreprise()->getNom() ?></b></p>
    									<p class="participant-name">Participant: <?= $achat->getUtilisateur()->getPrenoms()." ". $achat->getUtilisateur()->getNom()?></p>
									<?php } ?>
								</div>
							</div>

							<div class="formation-panier-price-delete">
								<button value="<?= $achat->getId() ?>" class="bi bi-x-lg btn-delete-formation-panier" title="Supprimer du panier" type="button"></button>
								<div class="one-formation-panier-price">$<?= $achat->getFormation()->getPrix() ?></div>
							</div>
						</div>
					</div>
					<?php }?>
						<div class="col-md-3 total-container">

							<div class="total-panier-container">
								<p>Total</p>
								<p class="panier-total">
									<span>$<?= $prixTotal ?></span>
								</p>
								<button type="button" title="Valider la facture" class="btn-panier-payment" id="<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>valid-facture<?php }else{ ?>particulier-valid-facture<?php }?>">Valider la facture</button>
							</div>
						</div>
						</div>
					<?php }?>
        			
        		</div>

			<?php if(!empty($listeFormation)){ ?>
			<div class="interresting-courses">
				<div class="container">
					<h1>Les formations qui pourraient vous interesser</h1>

					<div class="row">

						
        					<?php foreach ($listeFormation as $formation){ ?>
        					<div class="col-md-3 formation-item">
                    			<div class="card">
                    				<img src="<?= $formation->getIllustration() ?>" class="card-img-top" alt='<?= $formation->getTitre() ?>'>
                    				<div class="card-body">
                    						
                    					<h1 class="card-title">
                    						<a href="/formations/<?= $formation->getDomaine()->getTitreUrl() ?>/<?= $formation->getTitreUrl() ?>">
                    							<span class="cours-title"><?= $formation->getTitre() ?></span>
                    						</a>
                    					</h1>
                    					<div class="date"><?= Functions::formatFormationDate($formation->getDateDebut(), $formation->getDateFin()) ?></div>
                    					
                    				</div>
                    			</div>
                			</div>

					<?php }?>

					</div>


				</div>
			</div>
			<?php } ?>

			<!-- Footer -->
	<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/footer.php"); ?>
	</div>
	<!-- Container-fluid -->


	<?php require_once ($_SERVER ["DOCUMENT_ROOT"] . "/inc/other/js.php"); ?>
	<script type="text/javascript" src="/inc/js/compte/panier.js"></script>
	
</body>
</html>