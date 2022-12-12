<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/src/Utilisateur.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/manager/ContactManager.php");

session_start ();

use manager\ContactManager;
use utils\Functions;

Functions::redirectWhenNotConnexionAdmin($_SESSION["utilisateur"]);

$contactManager = new ContactManager();
$contact = $contactManager->getMail(intval(Functions::getValueChamp($_GET["mail"])));

if(!$contact->isView()){
    $contactManager->updateView($contact->getId());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>Mail de  | Wipreo administration</title>
<meta charset="utf-8" />
		<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/css.php"); ?>
		<link rel="stylesheet" href="/inc/css/w1-admin/contact.css" type="text/css" />
</head>
<body>

	<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/inc/other/w1-admin/menu.php"); ?>

	<div class="page-container" style="margin-top: 120px;">
		<div class="container" id="wiew-mail-container">
		
		<div class="mail-objet">
			<h3><?= $contact->getObjet() ?></h3>
		</div>
		
			<div class="user-info-container">
				<div><b>Nom &amp; prenoms: </b><?php if($contact->getUtilisateur()->getId() == null){echo $contact->getPrenoms()." ".$contact->getNom();}else{echo $contact->getUtilisateur()->getPrenoms()." ".$contact->getUtilisateur()->getNom();} ?></div>
				<div><b>Email: </b><span class="row-email"><?php if($contact->getUtilisateur()->getId() == null){echo $contact->getEmail();}else{echo $contact->getUtilisateur()->getEmail();} ?></span></div>
				<div><b>Telephone: </b><?php if($contact->getUtilisateur()->getId() == null){echo $contact->getTelephone();}else{echo $contact->getUtilisateur()->getTelephone();} ?></div>
				<div><b>Date envoi: </b><?= $contact->getDateEnvoi(); ?></div>
			</div>
			
			<div class="uer-message">
				<?= $contact->getMessage() ?>
				
				<button data-bs-toggle="modal" id="btn-reply-mail" data-bs-target="#modalSendMail" type="button" class="btn btn-primary btn-send-mail" title="Envoyer un mail à l'auteur">Repondre</button>
			</div>
			
			
			
		</div>
		<?php 
		  require_once ("../admins/modal/modal-send-mail.php");
		?>
	</div>
	
	<?php require_once($_SERVER["DOCUMENT_ROOT"]."/inc/other/w1-admin/js.php"); ?>
</body>
</html>