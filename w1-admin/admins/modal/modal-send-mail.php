<div class="modal fade" id="modalSendMail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSendMailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSendMailLabel">Envoyer un mail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

		<div class="form-floating mb-3">
			<input type="text" class="form-control" id="objet" placeholder="Objet" name="objet"> 
			<label for="objet">Objet</label>
			<div class="error"></div>
		</div>

		<div class="form-floating">
		  <textarea class="form-control" placeholder="Message" id="message" style="height: 200px" name="message"></textarea>
		  <label for="message">Message</label>
		  <div class="error"></div>
		</div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="btn-confirm-send-mail">Envoyer le mail</button>
      </div>
    </div>
  </div>
</div>