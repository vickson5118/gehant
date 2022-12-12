<?php 
namespace components\entreprise\modal;

use manager\AchatManager;

$achatManager = new AchatManager();
$listeFormation = $achatManager->getListeExistParticipant($_SESSION["utilisateur"]->getEntreprise()->getId(),$_SESSION["formationId"]);

?>
<!-- Modal -->
<div class="modal fade" id="staticBackdropAddExistParticipant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Ajouter un participant</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form>
					<div class="row">

						<div class="form-floating mb-4 col-md-12">
							<label for="user-infos" style="margin-left: 15px;">Nom &amp; Prenoms - Email</label>
							<select name="user-infos" id="user-infos" class="form-select" style="margin-top: 15px;">
								<?php foreach ($listeFormation as $utilisateur){ ?>
									<option value="<?= $utilisateur->getId() ?>"><?= $utilisateur->getNom()." ".$utilisateur->getPrenoms()." - ".$utilisateur->getEmail()  ?></option>
								<?php } ?>
							</select>
							<div class="error"></div>
						</div>

					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-add-exist-participant">Valider</button>
			</div>
		</div>
	</div>
</div>
<!-- modal -->