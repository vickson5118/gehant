<!-- Modal -->
<div class="modal fade" id="staticBackdropCreateObjectif"
	data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Ajouter un objectif</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>

			<form>
				<div class="modal-body">



					<div class="form-floating mb-4 col-md-12">
						<input type="text" class="form-control" id="nom-objectif" placeholder="Objectif" name="nom-objectif"> 
						<label for="nom-objectif" style="margin-left: 5px;">Objectif</label>
						<div class="error"></div>
					</div>


				</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
					<button type="button" class="btn btn-primary" id="btn-confirm-create-objectif">Valider</button>
				</div>
			
		</div>
	</div>
</div>
