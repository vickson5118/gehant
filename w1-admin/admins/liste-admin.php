<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");

session_start (); 

use manager\UtilisateurManager;
use utils\Constants;
use utils\Functions;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$utilisateurManager = new UtilisateurManager();
$listeAdmin = $utilisateurManager->getAllUser(Constants::COMPTE_ADMIN);

$adminActif = false;
$adminLocked = false;

foreach ($listeAdmin as $admin){
    
    if(!$admin->isBloquer()){
        $adminActif = true;
    }
    
    if($admin->isBloquer()){
        $adminLocked = true;
    }
}

?>
<!DOCTYPE html>
    <html lang="fr">
    	<head>
            <meta charset="utf-8">
            <title>Liste des administrateurs | Gehant</title>
        	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
    	</head>
    
    	<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">

			<!-- Button Creer admin modal -->
			<button type="button" class="btn btn-primary" data-bs-toggle="modal"
				data-bs-target="#staticBackdropCreateAdmin">Créer un administrateur</button>
				
				
			<!-- Liste admin bloqués -->
			<div class="panel panel-danger">
				<div class="panel-header">
					<h3>Les administrateurs bloqués</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$adminLocked){ ?>
						<h3 class="text-center">Aucun administrateur bloqué.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom &amp; prenoms</th>
								<th scope="col">Email</th>
								<th scope="col">Date</th>
								<th scope="col">Motif</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$indexCountLocked = 0;
							foreach ($listeAdmin as $admin){ 
							    if($admin->isBloquer()){
							        $indexCountLocked++;
							    ?>
							<tr>
								<th scope="row"><?= $indexCountLocked ?></th>
								<td class="row-name"><?= $admin->getPrenoms()." ".$admin->getNom() ?></td>
								<td class="row-email"><?= $admin->getEmail() ?></td>
								<td><?= $admin->getDateBlocage() ?></td>
								<td><?= $admin->getMotifBlocage() ?></td>
								<td>
									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
									<button value="<?= $admin->getId(); ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropDebloquerUser" type="button" class="btn btn-success btn-debloquer-user" title="Debloquer l'utilisateur"><i class="bi bi-unlock-fill"></i></button>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>

			<!-- Listes des admins fonctionnels -->
			<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les administrateurs</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$adminActif){ ?>
						<h3 class="text-center">Aucun administrateur enregistré.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom &amp; prenoms</th>
								<th scope="col">Email</th>
								<th scope="col">Inscription</th>
								<th scope="col">Connect</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php
							     $indexCountOnLine = 0;
							     foreach ($listeAdmin as $admin){ 
							    if(!$admin->isBloquer()){
							        $indexCountOnLine++;
							    ?>
							<tr style="text-align: center;">
								<th scope="row"><?= $indexCountOnLine ?></th>
								<td class="row-name"><?= $admin->getPrenoms(). " ".$admin->getNom(); ?></td>
								<td class="row-email"><?= $admin->getEmail(); ?></td>
								<td><?= $admin->getDateInscription(); ?></td>
								<?php if($admin->isConnect()){ ?>
									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
								<?php }else{ ?>
									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
								<?php } ?>
								
    								<td>
        								<?php if($_SESSION["utilisateur"]->getId() != $admin->getId()){  ?>
        									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
        									<button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropAnnulerAdmin" class="btn btn-success btn-annuler-admin" value="<?= $admin->getId(); ?>" title="Definir comme utilisateur"><i class="bi bi-sort-down"></i></button>
        									<button value="<?= $admin->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropBloquerUser" type="button" class="btn btn-danger btn-bloquer-user" title="Bloquer l'utilisateur"><i class="bi bi-lock-fill"></i></button>
        								<?php }?>
    								</td>
								
							</tr>
							<?php }} ?>
						</tbody>
					</table>
					<?php } ?>
				</div>
			</div>


		</div>
		<!-- Modal -->
		<?php
        require_once("modal/modal-create-admin.php");
        require_once("modal/modal-send-mail.php");
        require_once("modal/modal-annuler-admin.php");
        require_once("modal/modal-bloquer-user.php");
        require_once("modal/modal-debloquer-user.php");

        ?>

	</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-user.js" type="text/javascript"></script>
	</body>

</html>