<!-- Modal de creation d'un module-->
<div class="modal fade" id="staticBackdropEditModule" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropCreateModule">Editer un module</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-floating mb-3">
						<input type="text" class="form-control" id="edit-module-titre" placeholder="Titre du module" name="edit-module-titre"> 
						<label for="edit-module-titre">Titre du module</label>
						<div class="error"></div>
					</div>
					
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-primary btn-confirm-edit-module">Valider</button>
			</div>
		</div>
	</div>
</div>