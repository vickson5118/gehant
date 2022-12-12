<?php


require_once($_SERVER["DOCUMENT_ROOT"]."/manager/UtilisateurManager.php");
session_start ();

use manager\UtilisateurManager;
use utils\Constants;


if (($_SESSION ["utilisateur"]) == null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin" );
    exit ();
}else if($_SESSION ["utilisateur"] ->getTypeCompte()->getId() != Constants::COMPTE_ADMIN){
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
    exit ();
}


$utilisateurManager = new UtilisateurManager();
$listeUser = $utilisateurManager->getAllUser(Constants::COMPTE_STANDARD);

$userActif = false;
$userLocked = false;
$userInactif = false;

foreach ($listeUser as $user){
    
    if(!$user->getBloquer() && $user->getActive()){
        $userActif = true;
    }
    
    if($user->getBloquer()){
        $userLocked = true;
    }
    
    if(!$user->getActive()){
        $userInactif = true;
    }
}

?>
<?php session_start (); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des utilisateurs | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
	</head>

<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">


			<a href="" type="button" class="btn btn-primary"
				id="download-liste-achat"> Télécharger au format CSV </a>


			<!-- Listes des utilisateurs fonctionnels -->
			<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les utilisateurs actifs</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$userActif){ ?>
					<h3 class="text-center">Aucun utilisateur actif.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom &amp; prénoms</th>
								<th scope="col">Email</th>
								<th scope="col">Inscription</th>
								<th scope="col">Dernière connexion</th>
								<th scope="col">Connect</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							 $indexCountOnline = 0;
							foreach ($listeUser as $user){
							    if(!$user->getBloquer() && $user->getActive()){
							        $indexCountOnline++;
							    ?>
							    <tr style="text-align: center;">
    								<th scope="row"><?= $indexCountOnline ?></th>
    								<td class="row-name"><?= $user->getPrenoms()." ".$user->getNom() ?></td>
    								<td class="row-email"><?= $user->getEmail() ?></td>
    								<td><?= $user->getDateInscription() ?></td>
    								<td><?= $user->getDerniereConnexion() ?></td>
    								<?php if($user->getConnect()){ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-success">Oui</td>
    								<?php }else{ ?>
    									<td style="margin-top: 10px;" class="badge rounded-pill bg-danger">Non</td>
    								<?php } ?>
    								<td>
    									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
    									<button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdropDefinirAdmin" class="btn btn-success btn-definir-admin" value="<?= $user->getId(); ?>" title="Definir comme administrateur"><i class="bi bi-sort-up"></i></button>
    									<button value="<?= $user->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropBloquerUser" type="button" class="btn btn-danger btn-bloquer-user" title="Bloquer l'utilisateur"><i class="bi bi-lock-fill"></i></button>
    								</td>
    							</tr>
							<?php }}?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>


			<!-- Listes des utilisateurs inactifs -->
			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les utilisateurs inactif</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$userInactif){ ?>
						<h3 class="text-center">Aucun utilisateur inactif.</h3>
					<?php }else{ ?>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th scope="col">N°</th>
								<th scope="col">Nom &amp; prenoms</th>
								<th scope="col">Email</th>
								<th scope="col">Date inscription</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$indexCountInactif = 0;
							foreach ($listeUser as $user){
							    if(!$user->getActive()){
							        $indexCountInactif++;
							    ?>
								<tr style="text-align: center;">
    								<th scope="row"><?=$indexCountInactif ?></th>
    								<td class="row-name"><?= $user->getPrenoms()." ".$user->getNom() ?></td>
    								<td class="row-email"><?= $user->getEmail() ?></td>
    								<td><?= $user->getDateInscription() ?></td>
    								<td>
    									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
    								</td>
    							</tr>
    						<?php } } ?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>

			<!-- Liste admin bloqués -->
			<div class="panel panel-danger">
				<div class="panel-header">
					<h3>Les utilisateurs bloqués</h3>
				</div>
				<div class="panel-body table-responsive">
					<?php if(!$userLocked){ ?>
						<h3 class="text-center">Aucun utilisateur bloqué.</h3>
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
							foreach ($listeUser as $user){
							    if($user->getBloquer()){
							        $indexCountLocked++;
							    ?>
							    <tr>
    								<th scope="row"><?= $indexCountLocked ?></th>
    								<td class="row-name"><?= $user->getPrenoms()." ".$user->getNom() ?></td>
    								<td class="row-email"><?= $user->getEmail() ?></td>
    								<td><?= $user->getDateBlocage() ?></td>
    								<td><?= $user->getMotifBlocage() ?></td>
    								<td>
    									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
    									<button value="<?= $user->getId() ?>" data-bs-toggle="modal" data-bs-target="#staticBackdropDebloquerUser" type="button" class="btn btn-success btn-debloquer-user" title="Débloquer l'utilisateur"><i class="bi bi-unlock-fill"></i></button>
    								</td>
    							</tr>
							    
							<?php } } ?>
						</tbody>
					</table>
				<?php } ?>
				</div>
			</div>

		</div>

		<!-- Modal -->
		<?php
		require_once("../admins/modal/modal-send-mail.php");
		require_once("../admins/modal/modal-bloquer-user.php");
		require_once("../admins/modal/modal-debloquer-user.php");
		require_once("../admins/modal/modal-definir-admin.php");
        ?>
		
	</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
		<script src="/inc/js/w1-admin/liste-user.js" type="text/javascript"></script>
	</body>

</html>