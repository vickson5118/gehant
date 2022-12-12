<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/AchatManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Achat.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");

use manager\AchatManager;
use utils\Functions;

session_start ();


if( intval(Functions::getValueChamp($_GET["formation"])) == 0){
    http_response_code(404);
    exit();
}

$AchatManager = new AchatManager();
$listeAcheteur = $AchatManager->getListeFormationAcheteur(intval(Functions::getValueChamp($_GET["formation"])));

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Liste des acheteurs - Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
	</head>
	
	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
    		<div class="container-fluid">
    				
    				<div class="panel panel-warning">
				<div class="panel-header">
					<h3>Les acheteurs</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listeAcheteur)){ ?>
						<h3 class="text-center">Aucun acheteur enregistré.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Entreprise</th>
								<th scope="col">Benéficiaire</th>
								<th scope="col">Email</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($listeAcheteur as $key => $acheteur){
                                ?>
								
								<tr style="text-align: center;">
								<th scope="row"><?= ($key+1) ?></th>
								<td><?= $acheteur->getEntreprise()->getNom() == null ? "<b>Néant</b>" : $acheteur->getEntreprise()->getNom() ?></td>
								<td class="row-name"><?= $acheteur->getUtilisateur()->getPrenoms() ?> <?= $acheteur->getUtilisateur()->getNom() ?></td>
								<td class="row-email"><?= $acheteur->getUtilisateur()->getEmail() ?></td>
								<td>
									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail à l'auteur"><i class="bi bi-envelope"></i></button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } ?>
				</div>
			</div>
    				
    		</div>
		</div>
		
		<?php
        require_once("../admins/modal/modal-send-mail.php");
        ?>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	</body>
	
</html>
