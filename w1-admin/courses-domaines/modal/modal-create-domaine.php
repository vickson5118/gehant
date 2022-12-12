
<!-- Modal -->
<div class="modal fade" id="staticBackdropCreateDomaine"
	data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Créer un domaine de formation</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
			<form method="post" enctype="multipart/form-data" id="add-domaine-form">
			<div class="modal-body">
				
					<div class="row">
					
					<div class="col-md-4 mb-3">
							
    					<label for="domaineIllustration" class="form-label domaineIllustrationLabel"> 
    						<i class="bi bi-cloud-arrow-up-fill"></i> 
    						<img src=""  alt="" style="max-width: 300px; max-height: 300px;" />
    					</label> 
    						<input class="form-control" type="file" id="domaineIllustration" name="domaineIllustration" accept="image/*">
    						<span class="illustration-info">
    							L'image doit être au format 1920 * 900. <br />
    							La taille de l'image ne doit pas excéder 10Mo.
    						</span>
    						<div class="error" style="margin-left: 25px;margin-top: -40px;"></div>
					</div>
						
						<div class="col-md-8" style="margin-top: 50px;">
						
							<div class="row">
							<div class="form-floating mb-4 col-md-12">
							<input type="text" class="form-control" id="titre" placeholder="Titre du domaine" name="titre"> 
							<label for="titre" style="margin-left: 15px;">Titre du domaine</label>
							<div class="error"></div>
						</div>
						
						<div class="form-floating" style="margin-top: 15px;">
						  <textarea class="form-control" placeholder="Description" id="description" style="height: 200px;" name="description"></textarea>
						  <label for="description" style="margin-left: 15px;">Description</label>
						  <div class="error"></div>
						</div>
						</div>
						
						</div>

					</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-primary" id="btn-create-domaine">Valider</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- modal -->