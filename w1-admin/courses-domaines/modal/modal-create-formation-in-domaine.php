<!-- Modal de creation d'une formation-->
<div class="modal fade" id="staticBackdropCreateFormationInDomaine" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Créer une formation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-floating mb-3">
						<input type="text" class="form-control" id="titre" placeholder="Titre de la formation" name="titre"> 
						<label for="titre">Titre de la formation</label>
						<div class="error"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary"data-bs-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-create-formation-in-domaine">Valider</button>
			</div>
		</div>
	</div>
</div>