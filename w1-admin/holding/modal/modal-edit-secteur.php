<!-- Modal -->
<div class="modal fade" id="staticBackdropEditSecteur"
	data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Editer un secteur
					d'activité</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"
					aria-label="Close"></button>
			</div>

			<form>
				<div class="modal-body">



					<div class="form-floating mb-4 col-md-12">
						<input type="text" class="form-control" id="secteur" placeholder="Secteur d'activité" name="secteur"> 
						<label for="secteur" style="margin-left: 15px;">Secteur d'activité</label>
						<div class="error"></div>
					</div>


				</div>
				</form>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
					<button type="button" class="btn btn-primary" id="btn-confirm-edit-secteur">Valider</button>
				</div>
			
		</div>
	</div>
</div>