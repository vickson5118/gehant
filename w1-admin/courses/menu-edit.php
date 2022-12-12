<?php 
    require_once($_SERVER["DOCUMENT_ROOT"]."/src/Formation.php");
    session_start();
?>
<div class="col-md-3 edit-menu-container">
	<div class="palinifier-container">
		<h5>Planifier le cours</h5>
		<ul>
			<li><a href="/w1-admin/creation/formation/<?= $_SESSION["formation"]->getDomaine()->getTitreUrl() ?>/<?= $_SESSION["formation"]->getTitreUrl() ?>/prerequis" id="link-prerequis" class="create-formation-link">Prerequis</a></li>
			<li><a href="/w1-admin/creation/formation/<?= $_SESSION["formation"]->getDomaine()->getTitreUrl() ?>/<?= $_SESSION["formation"]->getTitreUrl() ?>/cibles" id="link-cibles" class="create-formation-link">Cibles</a></li>
			<li><a href="/w1-admin/creation/formation/<?= $_SESSION["formation"]->getDomaine()->getTitreUrl() ?>/<?= $_SESSION["formation"]->getTitreUrl() ?>/objectifs" id="link-objectifs" class="create-formation-link">Objectifs</a></li>
		</ul>
	</div>

	<div class="create-contenu-container">
		<h5>Créer votre contenu</h5>
		<ul>
			<li><a href="/w1-admin/creation/formation/<?= $_SESSION["formation"]->getDomaine()->getTitreUrl() ?>/<?= $_SESSION["formation"]->getTitreUrl() ?>/programme" id="link-programme">Programme</a></li>
			<li><a href="/w1-admin/creation/formation/<?= $_SESSION["formation"]->getDomaine()->getTitreUrl() ?>/<?= $_SESSION["formation"]->getTitreUrl() ?>/presentation" id="link-presentation">Presentation</a></li>
		</ul>
	</div>

	<div class="finalise-contenu-container">
		<?php if(!$_SESSION["formation"]->getRedactionFinished()){ ?>
		<button type="button" id="btn-send-evaluate">Envoyer pour evaluation</button>
		<?php }?>
	</div>
	
</div>