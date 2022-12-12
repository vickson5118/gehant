<div class="modal fade" id="staticBackdropBloquerUser" tabindex="-1" aria-labelledby="bloquerUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bloquerUserModalLabel">Bloquer un utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        
        <form>
          	
        	<div class="form-floating mb-3">
			  <input type="text" class="form-control" id="motif-blocage" placeholder="Motif du blocage" name="motif-blocage">
			  <label for="motif-blocage">Motif du blocage</label>
			  <div class="error"></div>
			</div>
        	
        	
        	<span class="text-danger" id="bloquer-info"></span>
        	
        </form>
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="btn-confirm-bloquer-user">Valider</button>
      </div>
    </div>
  </div>
</div>