<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Achat.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Formation.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/AchatManager.php");

use manager\AchatManager;
use utils\Constants;
use utils\Functions;

session_start();

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$achatManager = new AchatManager();
$listeAchatParticulierNotConfirm = $achatManager -> getListeAchatParticulierNotComfirmPaid();
$listeAchatEntrepriseNotConfirm = $achatManager -> getListeAchatEntrepriseNotConfirmPaid();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des achats éffectués | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
	</head>

<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">

			<div class="panel panel-warning">
				<div class="panel-header">
					<h3>Les achats en attente de validation - Particulier</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listeAchatParticulierNotConfirm)){ ?>
						<h3 class="text-center">Aucun achat en attente.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Titre</th>
								<th scope="col">Nom</th>
								<th scope="col">Email</th>
								<th scope="col">Tel</th>
								<th scope="col">Date Ins.</th>
								<th scope="col">Date Form.</th>
								<th scope="col" style="display: none;">Lieu</th>
								<th scope="col" style="display: none;">Domaine url</th>
								<th scope="col" style="display: none;">Formation url</th>
								<th scope="col">Prix</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
                                foreach($listeAchatParticulierNotConfirm as $key => $achat){
                                ?>
								
								<tr style="text-align: center;">
								<th scope="row"><?= ($key+1) ?></th>
								<td class="formation-title"><?= $achat->getFormation()->getTitre() ?></td>
								<td class="row-name"><?= $achat->getUtilisateur()->getPrenoms() ?> <?= $achat->getUtilisateur()->getNom() ?></td>
								<td class="row-email"><?= $achat->getUtilisateur()->getEmail() ?></td>
								<td class="row-tel"><?= $achat->getUtilisateur()->getTelephone() ?></td>
								<td><?= $achat->getDateInscription() ?></td>
								<td class="row-date"><?= Functions::formatFormationDate($achat->getFormation()->getDateDebut(), $achat->getFormation()->getDateFin()) ?></td>
								<td class="row-lieu" style="display: none;"><?= $achat->getFormation()->getLieu() ?></td>
								<td class="row-domaine-url" style="display: none;"><?= $achat->getFormation()->getDomaine()->getTitreUrl() ?></td>
								<td class="row-formation-url" style="display: none;"><?= $achat->getFormation()->getTitreUrl() ?></td>
								<td class="row-prix">$<?= $achat->getFormation()->getPrix() ?></td>
								<td>
									<button data-bs-toggle="modal" data-bs-target="#staticBackdropConfirmPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-success btn-modal-confirm-particulier-paiement" title="Confirmer le paiement">
										<i class="bi bi-check-lg"></i>
									</button>
									
									<?php if($achat->isPaidForced()){ ?>
									<button data-bs-toggle="modal" data-bs-target="#staticBackdropConfirmUnlockedPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-danger btn-modal-confirm-particulier-unlocked-paiement" title="Débloquer l'achat">
										<i class="bi bi-unlock-fill"></i>
									</button>
									<?php }else{ ?>
										<button data-bs-toggle="modal" data-bs-target="#staticBackdropConfirmLockedPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-primary btn-modal-confirm-particulier-locked-paiement" title="Bloquer l'achat">
    										<i class="bi bi-lock-fill"></i>
    									</button>
									<?php }?>
									
									<button data-bs-toggle="modal" data-bs-target="#staticBackdropDeleteConfirmPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-danger btn-modal-delete-confirm-particulier-paiement" title="Supprimer l'achat">
										<i class="bi bi-x-lg"></i>
									</button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } ?>
				</div>
			</div>
			
			
			<div class="panel panel-warning">
				<div class="panel-header">
					<h3>Les achats en attente de validation - Entreprise</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listeAchatEntrepriseNotConfirm)){ ?>
						<h3 class="text-center">Aucun achat en attente.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Titre</th>
								<th scope="col" style="display: none;">Nom</th>
								<th scope="col">Nom</th>
								<th scope="col" style="display: none;">Email</th>
								<th scope="col">Tel</th>
								<th scope="col">Ent.</th>
								<th scope="col">Ent. Mail</th>
								<th scope="col">Ent. Tel</th>
								<th scope="col">Date Ins.</th>
								<th scope="col">Date Form.</th>
								<th scope="col" style="display: none;">Lieu</th>
								<th scope="col" style="display: none;">Domaine url</th>
								<th scope="col" style="display: none;">Formation url</th>
								<th scope="col">Prix</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							 foreach($listeAchatEntrepriseNotConfirm as $key => $achat){
                            ?>
								
								<tr style="text-align: center;">
    								<th scope="row"><?= ($key+1) ?></th>
    								<td class="formation-title"><?= $achat->getFormation()->getTitre() ?></td>
    								<td class="row-name" style="display: none;"><?= $achat->getUtilisateur()->getPrenoms() ?> <?= $achat->getUtilisateur()->getNom() ?></td>
    								<td> <?= $achat->getUtilisateur()->getNom() ?></td>
    								<td class="row-email" style="display: none;"><?= $achat->getUtilisateur()->getEmail() ?></td>
    								<td class="row-tel"><?= $achat->getUtilisateur()->getTelephone() ?></td>
    								<td class="row-entrep-nom"><?= $achat->getEntreprise()->getNom() ?></td>
    								<td class="row-entrep-mail"><?= $achat->getEntreprise()->getUtilisateur()->getEmail() ?></td>
    								<td><?= $achat->getEntreprise()->getUtilisateur()->getTelephone() ?></td>
    								<td><?= $achat->getDateInscription() ?></td>
    								<td class="row-date"><?= Functions::formatFormationDate($achat->getFormation()->getDateDebut(), $achat->getFormation()->getDateFin()) ?></td>
    								<td class="row-lieu" style="display: none;"><?= $achat->getFormation()->getLieu() ?></td>
    								<td class="row-domaine-url" style="display: none;"><?= $achat->getFormation()->getDomaine()->getTitreUrl() ?></td>
									<td class="row-formation-url" style="display: none;"><?= $achat->getFormation()->getTitreUrl() ?></td>
    								<td class="row-prix">$<?= $achat->getFormation()->getPrix() ?></td>
    								<td>
    									<button data-bs-toggle="modal" data-bs-target="#staticBackdropConfirmPaiementEntreprise" value="<?= $achat->getId() ?>" type="button" class="btn btn-success btn-modal-confirm-entreprise-paiement" title="Confirmer le paiement">
    										<i class="bi bi-check-lg"></i>
    									</button>
    									<?php if($achat->isPaidForced()){ ?>
        									<button data-bs-toggle="modal" data-bs-target="#staticBackdropConfirmUnlockedPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-danger btn-modal-confirm-particulier-unlocked-paiement" title="Débloquer l'achat">
        										<i class="bi bi-unlock-fill"></i>
        									</button>
    									<?php }else{ ?>
    										<button data-bs-toggle="modal" data-bs-target="#staticBackdropConfirmLockedPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-primary btn-modal-confirm-particulier-locked-paiement" title="Bloquer l'achat">
        										<i class="bi bi-lock-fill"></i>
        									</button>
    									<?php }?>
    									<button data-bs-toggle="modal" data-bs-target="#staticBackdropDeleteConfirmPaiement" value="<?= $achat->getId() ?>" type="button" class="btn btn-danger btn-modal-delete-confirm-entreprise-paiement" title="Supprimer l'achat">
    										<i class="bi bi-x-lg"></i>
    									</button>
									</td>
							</tr>
							<?php }  ?>
						</tbody>
					</table>
					<?php } ?>
				</div>
			</div>

		</div>
			
			<?php
                require_once ("modal/modal-confirm-paiement.php");
                require_once ("modal/modal-confirm-locked-paiement.php");
                require_once ("modal/modal-confirm-unlocked-paiement.php");
                require_once ("modal/modal-confirm-paiement-entreprise.php");
                require_once ("modal/modal-delete-paiement.php");
             ?>
			
		</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-achat.js" type="text/javascript"></script>
</body>

</html>