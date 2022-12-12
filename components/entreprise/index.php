<?php 

namespace components\entreprise;

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");

session_start ();

use manager\AchatManager;
use utils\Functions;


Functions::redirectWhenNotConnexionEntreprise($_SESSION["utilisateur"]);

$achatManager = new AchatManager();
$listeFormationNotPaid = $achatManager->getListeFormationEntrepriseNotConfirmPaid($_SESSION["utilisateur"]->getEntreprise()->getId());

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Entreprise | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/entreprise/index.css" type="text/css" />
	</head>
	
	<body>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/header.php"); ?>
	
		<div class="container-fluid">
		
			<div class="container" id="entreprise-container">
				
				<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail" style="margin-left: -10px;margin-bottom: 20px;">Envoyez un mail <i class="bi bi-envelope"></i></button>
				
				<div class="row">
					<div class="col-md-6 formations-buy-container">
						<a href="/espace-client/entreprise/formations">Mes formations <span>Retrouvez dans cette section, les formations payées. En plus vous pouvez rajouter des collaborateurs aux formations de votre choix.</span></a>
						</div>
    				<div class="col-md-6 factures-container"><a href="/espace-client/entreprise/factures">Mes factures <span>Retrouvez les différentes factures générées en format PDF ainsi que les informations se rapportant à ces factures. Vous pouvez également télécharger vos différentes factures.</span></a></div>
				</div>
				
				<div class="panel panel-dark" style="margin-top: 70px;">
				<div class="panel-header">
					<h3>Les formations impayées</h3>
				</div>
				<div class="table-responsive-md panel-body" style="padding-top: 20px;padding-bottom: 20px;">
					<?php if(empty($listeFormationNotPaid)){ ?>
					<h3 class="text-center">Aucune formation impayée</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Benéficiaire</th>
								<th scope="col" class="col-email">Email</th>
								<th scope="col">Titre</th>
								<th scope="col">Prix</th>
								<th scope="col">Date</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listeFormationNotPaid as $key => $achat){
							    ?>
							    <tr style="text-align: center;">
    								<th scope="row"><?= ($key+1) ?></th>
    								<td class="row-name"><?= $achat->getUtilisateur()->getPrenoms()." ".$achat->getUtilisateur()->getNom() ?></td>
    								<td class="row-email"><?= $achat->getUtilisateur()->getEmail() ?></td>
    								<td><?= $achat->getFormation()->getTitre() ?></td>
    								<td>$<?= $achat->getFormation()->getPrix() ?></td>
    								<td><?= Functions::formatFormationDate($achat->getFormation()->getDateDebut(), $achat->getFormation()->getDateFin()) ?></td>
    							</tr>
							<?php }?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>
				
			</div>
			
			<?php
                require_once("modal/modal-send-mail.php");
             ?>
			<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/footer.php"); ?>
		</div>
		
		
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/js.php"); ?>
		<script src="/inc/js/entreprise/index.js" type="text/javascript"></script>
	</body>
	
</html>