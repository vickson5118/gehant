<?php 
namespace components\entreprise\modal;
?>
<!-- Modal -->
<div class="modal fade" id="staticBackdropAddParticipant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Ajouter un participant</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form>
					<div class="row">

						<div class="form-floating mb-4 col-md-6">
							<input type="text" class="form-control" id="nom" placeholder="Nom" name="nom"> 
							<label for="nom" style="margin-left: 15px;">Nom</label>
							<div class="error"></div>
						</div>

						<div class="form-floating mb-4 col-md-6">
							<input type="text" class="form-control" id="prenoms" placeholder="Prenoms" name="prenoms"> 
							<label for="prenoms" style="margin-left: 15px;">Prenoms</label>
							<div class="error"></div>
						</div>

						<div class="form-floating mb-4 col-md-6">
							<input type="text" class="form-control" id="email" placeholder="Adresse email" name="email"> 
							<label for="email" style="margin-left: 15px;">Adresse email</label>
							<div class="error"></div>
						</div>
						
						<div class="form-floating mb-4 col-md-6">
							<input type="text" class="form-control" id="telephone" placeholder="Téléphone" name="telephone"> 
							<label for="telephone" style="margin-left: 15px;">Téléphone</label>
							<div class="error"></div>
						</div>
						
						<div class="form-floating mb-3 col-md-12">
							<input type="text" class="form-control" id="fonction" placeholder="Fonction" name="fonction"> 
							<label for="fonction" style="margin-left: 15px;">Fonction</label>
							<div class="error"></div>
						</div>

					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-add-participant">Valider</button>
			</div>
		</div>
	</div>
</div>
<!-- modal -->