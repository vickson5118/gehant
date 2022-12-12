<?php 
  
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/src/Contact.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ContactManager.php");
session_start ();

use manager\ContactManager;
use utils\Constants;

if (($_SESSION ["utilisateur"]) == null) {
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"]."/w1-admin" );
    exit ();
}else if($_SESSION ["utilisateur"] ->getTypeCompte()->getId() != Constants::COMPTE_ADMIN){
    header ( "Location: http://" . $_SERVER ["SERVER_NAME"] );
    exit ();
}

$contactManager = new ContactManager();
$ListeParticulierContact = $contactManager->getListeParticulierContact();
$ListeEntrepriseContact = $contactManager->getListeEntrepriseContact();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Liste des contacts | Gehant</title>
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/w1-admin/contact.css" type="text/css" />
	</head>

<body>
	
		
		<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>
	
		<div class="page-container">
		<div class="container-fluid">
		
		<div class="panel panel-success">
				<div class="panel-header">
					<h3>Les entreprises</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($ListeEntrepriseContact)){ ?>
						<h3 class="text-center">Aucun message d'une entreprise.</h3>
					<?php }else{ ?>
    					<table class="table table-hover table-bordered">
    						<thead>
    							<tr>
    								<th scope="col">N°</th>
    								<th scope="col">Entreprise</th>
    								<th scope="col">Email</th>
    								<th scope="col">Telephone</th>
    								<th scope="col">Objet</th>
    								<th scope="col">Date</th>
    								<th scope="col">Actions</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php foreach ($ListeEntrepriseContact as $key => $contactEntreprise){ ?>
        							<tr>
        								<th scope="row"><?=  $key+1 ?><?php if(!$contactEntreprise->getView()){?><span class="not-view"></span><?php }?></th>
        								<td><?= $contactEntreprise->getUtilisateur()->getEntreprise()->getNom() ?></td>
        								<td class="row-email"><?= $contactEntreprise->getUtilisateur()->getEmail() ?></td>
        								<td><?= $contactEntreprise->getUtilisateur()->getTelephone() ?></td>
        								<td><?= $contactEntreprise->getObjet() ?></td>
        								<td><?= $contactEntreprise->getDateEnvoi() ?></td>
        								<td>
        									<a href="/w1-admin/contacts/<?= $contactEntreprise->getId() ?>" class="btn btn-success" title="Aperçu du mail">
        										<i class="bi bi-eye-fill"></i>
        									</a>
        
        									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
        								</td>
        							</tr>
    							<?php }?>
    						</tbody>
    					</table>
					<?php }?>
				</div>
			</div>


			<div class="panel panel-primary">
				<div class="panel-header">
					<h3>Les particuliers</h3>
				</div>
				<div class="panel-body">
					<?php if(empty($ListeParticulierContact)){ ?>
						<h3 class="text-center">Aucun message envoyé.</h3>
					<?php }else{ ?>
    					<table class="table table-hover table-bordered">
    						<thead>
    							<tr>
    								<th scope="col">N°</th>
    								<th scope="col">Nom &amp; prenoms</th>
    								<th scope="col">Email</th>
    								<th scope="col">Telephone</th>
    								<th scope="col">Objet</th>
    								<th scope="col">Date</th>
    								<th scope="col">Actions</th>
    							</tr>
    						</thead>
    						<tbody>
    							<?php foreach ($ListeParticulierContact as $key => $contact){ ?>
        							<tr>
        								<th scope="row"><?=  $key+1 ?><?php if(!$contact->getView()){?><span class="not-view"></span><?php }?></th>
        								<td><?= $contact->getPrenoms()." ".$contact->getNom() ?></td>
        								<td class="row-email"><?= $contact->getEmail() ?></td>
        								<td><?= $contact->getTelephone() ?></td>
        								<td><?= $contact->getObjet() ?></td>
        								<td><?= $contact->getDateEnvoi() ?></td>
        								<td>
        									<a href="/w1-admin/contacts/<?= $contact->getId() ?>" class="btn btn-success" title="Aperçu du mail">
        										<i class="bi bi-eye-fill"></i>
        									</a>
        
        									<button data-bs-toggle="modal" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail"><i class="bi bi-envelope"></i></button>
        								</td>
        							</tr>
    							<?php }?>
    						</tbody>
    					</table>
					<?php }?>
				</div>
			</div>
			
			

		</div>
		
		<?php 
		  require_once("../admins/modal/modal-send-mail.php");
		?>
		
	</div>
		
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
	</body>

</html>