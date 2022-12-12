
<!-- Modal -->
<div class="modal fade" id="staticBackdropEditDomaine"data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Editer un domaine de formation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<form method="post" enctype="multipart/form-data" id="edit-domaine-form">
			<div class="modal-body">
				
					<div class="row">
					
					<div class="col-md-4 mb-3">
							
					<label for="editDomaineIllustration" class="form-label editDomaineIllustrationLabel"> 
						<img src="" />
					</label> 
						<input class="form-control" type="file" id="editDomaineIllustration" name="editDomaineIllustration" accept="image/*">
						<span class="illustration-info">
    							L'image doit être au format 1920 * 900. <br />
    							La taille de l'image ne doit pas excéder 10Mo.
    						</span>
						<div class="error" style="margin-left: 30px;margin-top: -30px;"></div>
					</div>
						
						<div class="col-md-8" style="margin-top: 50px;">
						
							<div class="row">
							<div class="form-floating mb-4 col-md-12">
							<input type="text" class="form-control" id="edit-titre" placeholder="Titre du domaine" name="editTitre"> 
							<label for="edit-titre" style="margin-left: 15px;">Titre du domaine</label>
							<div class="error"></div>
						</div>
						
						<div class="form-floating mb-4" style="margin-top: 15px;">
						  <textarea class="form-control" placeholder="Description" id="edit-description" style="height: 200px;" name="editDescription"></textarea>
						  <label for="edit-descprition" style="margin-left: 15px;">Description</label>
						  <div class="error"></div>
						</div>
						</div>
						
						</div>

					</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-primary" id="btn-confirm-edit-domaine">Valider</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->