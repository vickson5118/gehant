<?php 

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/EntrepriseManager.php");

session_start ();

use manager\UtilisateurManager;
use utils\Functions;
use manager\EntrepriseManager;
use utils\Constants;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$entrepriseId = intval(Functions::getValueChamp($_GET ["entreprise"]));

if($entrepriseId == 0){
    http_response_code(404);
    exit();
}

$utilisateurManager = new UtilisateurManager();
$entrepriseManager = new EntrepriseManager();

$listeUser = $utilisateurManager->getAllEntrepriseUser($entrepriseId);
$entrepriseName = $entrepriseManager->getName($entrepriseId);

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>  Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
	</head>
	
	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
			<div class="container-fluid">
				
				<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les employés de l'entreprise <b><?= $entrepriseName ?></b></h3>
				</div>
				<div class="panel-body">
					<?php if(empty($listeUser)){ ?>
					<h3 class="text-center">Aucun employé enrégistré.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom &amp; prénoms</th>
								<th scope="col">Email</th>
								<th scope="col">Entreprise</th>
								<th scope="col">Inscription</th>
								<th scope="col">Bloquer</th>
								<th scope="col">D. connexion</th>
								<th scope="col">Connecter</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listeUser as $key => $user){?>
							    <tr style="text-align: center;">
    								<th scope="row"><?= ($key+1) ?></th>
    								<td class="row-name"><?= $user->getPrenoms()." ".$user->getNom() ?></td>
    								<td class="row-email"><?= $user->getEmail() ?></td>
    								
    								<?php if($user->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
    								<?php }else{ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
    								<?php } ?>
    								
    								<td><?= $user->getDateInscription() ?></td>
    								
    								<?php if($user->isBloquer()){ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
    								<?php }else{ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
    								<?php } ?>
    								
    								<td><?= $user->getDerniereConnexion() ?></td>
    								
    								<?php if($user->isConnect()){ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
    								<?php }else{ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
    								<?php } ?>

    								<td>
    									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
    								</td>
    							</tr>
							<?php }?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>
				
				
			</div>
			
			<!-- Modals -->
			<?php
            require_once("../admins/modal/modal-send-mail.php");
            ?>
			
		</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	</body>
	
</html>