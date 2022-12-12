<!-- Modal de creation d'une formation-->
<div class="modal fade" id="staticBackdropEditPays" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Editer un pays</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form>
				
					<div class="form-floating">
						<input type="text" class="form-control" id="edit-nom"placeholder="Nom" name="edit-nom"> 
						<label for="edit-nom">Nom</label>
						<div class="error"></div>
					</div>
				
					
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary"data-bs-dismiss="modal">Annuler</button>
				<button type="button" class="btn btn-primary" id="btn-confirm-edit-pays">Valider</button>
			</div>
		</div>
	</div>
</div>