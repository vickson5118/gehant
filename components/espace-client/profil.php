<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/PaysManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/NombreEmployeManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/ObjectifManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/manager/SecteurManager.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/Utilisateur.php");
session_start();
use manager\PaysManager;
use utils\Constants;
use manager\NombreEmployeManager;
use manager\ObjectifManager;
use manager\SecteurManager;
$paysManager = new PaysManager();
$nombreEmployeManager = new NombreEmployeManager();
$objectifManager = new ObjectifManager();
$secteurManager = new SecteurManager();
$listePays = $paysManager -> getAllPays();
$listeNombreEmploye = $nombreEmployeManager -> getAll();
$listeObjectif = $objectifManager -> getAll();
$listeSecteur = $secteurManager -> getAll();
?>

<div class="container">
	
	<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>
	
		<div class="infos-entreprise-container">

		<p>
			<img src="/inc/images/icones/espace-client/user.png"
				alt="Icone informations personnelles" /> <span>Mon entreprise</span>
		</p>

		<form class="form-box">

			<div class="row">
				<div class="col-md-6 form-input-container">
					<label for="entreprise">Nom de l'entreprise</label> <input
						type="text" name="entreprise" id="entreprise" required="required"
						value="<?= $_SESSION["utilisateur"]->getEntreprise() -> getNom();?>" />
					<div class="error"></div>
				</div>

				<div class="col-md-6 form-input-container">
					<label for="nb-employes">Nombre d'employés</label>
                    <select name="nb-employes" id="nb-employes">
    					<?php foreach ($listeNombreEmploye as $tranche){ ?>
    					<option value="<?= $tranche->getId();?>"
							<?php if($tranche->getId() == $_SESSION["utilisateur"]->getEntreprise()->getNombreEmploye()->getId()){ ?>
							selected="selected" <?php }?>>
    					   	<?= $tranche->getTranche();?>
    					   	</option>
    						
    					<?php } ?>
    				</select>
					<div class="error"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 form-input-container">
					<label for="secteur">Secteur d'activité</label> <select
						name="secteur" id="secteur">
    					<?php foreach ($listeSecteur as $secteur){ ?>
    					<option value="<?= $secteur->getId();?>"
							<?php if($secteur->getId() == $_SESSION["utilisateur"]->getEntreprise()->getSecteur()->getId()){ ?>
							selected="selected" <?php }?>>
    					   	<?= $secteur->getNom();?>
    					   	</option>
    						
    					<?php } ?>
    				</select>
					<div class="error"></div>
				</div>

				<div class="col-md-6 form-input-container">
					<label for="objectif">Objectif</label> <select name="objectif"
						id="objectif">
    					<?php foreach ($listeObjectif as $objectif){ ?>
    					<option value="<?= $objectif->getId();?>"
							<?php if($objectif->getId() == $_SESSION["utilisateur"]->getEntreprise()->getObjectif()->getId()){ ?>
							selected="selected" <?php }?>>
    					   	<?= $objectif->getNom();?>
    					   	</option>
    						
    					<?php } ?>
    				</select>
					<div class="error"></div>
				</div>
			</div>




			<button type="button" id="btn-update-entreprise-infos"
				value="<?= $_SESSION["utilisateur"]->getEntreprise()->getId() ?>">Modifier
				mon entreprise</button>

		</form>

	</div>
	<?php } ?>

	<div class="infos-perso-container">

		<p>
			<img src="/inc/images/icones/espace-client/user.png"
				alt="Icone informations personnelles" /> <span>Mes informations</span>
		</p>

		<form class="form-box">

			<div class="row">
				<div class="col-md-4 form-input-container">
					<label for="nom">Nom*</label> <input type="text" name="nom"
						id="nom" required="required"
						value="<?= $_SESSION["utilisateur"] -> getNom();?>" />
					<div class="error"></div>
				</div>

				<div class="col-md-4 form-input-container">
					<label for="prenoms">Prenoms*</label> <input type="text"
						name="prenoms" id="prenoms" required="required"
						value="<?= $_SESSION["utilisateur"] -> getPrenoms();?>" />
					<div class="error"></div>
				</div>

				<div class="col-md-4 form-input-container">
					<label for="telephone">Téléphone</label> <input type="text"
						name="telephone" id="telephone" required="required"
						value="<?= $_SESSION["utilisateur"] -> getTelephone();?>" />
					<div class="error"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 form-input-container">
					<label for="naissance">Date de naissance</label> <input type="text"
						name="naissance" id="naissance" readonly="readonly"
						required="required"
						value="<?= $_SESSION["utilisateur"] -> getDateNaissance();?>" />
					<div class="error"></div>
				</div>

				<div class="col-md-4 form-input-container">
					<label for="pays">Pays d'origine</label> <select name="pays"
						id="pays">
    					<?php foreach ($listePays as $pays){ ?>
    					<option value="<?= $pays->getId();?>"
							<?php if($pays->getId() == $_SESSION["utilisateur"]->getPays()->getId()){ ?>
							selected="selected" <?php }?>>
    					   	<?= $pays->getNom();?>
    					   	</option>
    						
    					<?php } ?>
    				</select>
					<div class="error"></div>
				</div>


				<div class="col-md-4 form-input-container fonction-container">
					<label for="fonction">Fonction</label> <input type="text"
						name="fonction" id="fonction" required="required"
						value="<?= $_SESSION["utilisateur"] -> getFonction();?>" />
					<div class="error"></div>
				</div>
			</div>

			<button type="button" id="update-infos-perso">Modifier mon profil</button>

		</form>

	</div>

	<div class="infos-email-container">

		<p>
			<img src="/inc/images/icones/espace-client/email.png"
				alt="Icone Email" /> <span>Adresse E-mail</span>
		</p>

		<form class="form-box">

			<div class="row">

				<div class="col-md-6 form-input-container">
					<label for="email">Ancienne adresse E-mail*</label> <input
						type="email" name="email" id="email" required="required" />
					<div class="error"></div>
				</div>

				<div class="col-md-6 form-input-container">
					<label for="new-email">Nouvelle adresse E-mail*</label> <input
						type="email" name="new-email" id="new-email" required="required" />
					<div class="error"></div>
				</div>

			</div>

			<button type="button" id="btn-update-email">Modifier mon adresse
				E-mail</button>

		</form>

	</div>

	<div class="infos-password-container">

		<p>
			<img src="/inc/images/icones/espace-client/password.png"
				alt="Icone Mot de passe" /> <span>Mot de passe</span>
		</p>

		<form class="form-box">

			<div class="row">
				<div class="col-md-4 form-input-container">
					<label for="old-password">Ancien mot de passe*</label> <input
						type="password" name="old-password" id="old-password"
						required="required" />
					<div class="error"></div>
				</div>

				<div class="col-md-4 form-input-container">
					<label for="password">Nouveau mot de passe*</label> <input
						type="password" name="password" id="password" required="required" />
					<div class="error"></div>
				</div>

				<div class="col-md-4 form-input-container">
					<label for="repeat-password">Confirmer le mot de passe*</label> <input
						type="password" name="repeat-password" id="repeat-password"
						required="required" />
					<div class="error"></div>
				</div>
			</div>

			<button type="button" id="btn-update-password">Modifier mon mot de
				passe</button>

		</form>

	</div>

</div>


<script src="/inc/js/espace-client/profil.js" type="text/javascript"></script>
<?php if($_SESSION["utilisateur"]->getTypeCompte()->getId() == Constants::COMPTE_ENTREPRISE){ ?>
<script src="/inc/js/entreprise/update-entreprise-infos.js"
	type="text/javascript"></script>
<?php }?>
