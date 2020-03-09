<div class='modal fade' id='formulario' data-backdrop="static">
	<div class='modal-dialog modal-dialog-centered modal-lg'>
		<div class='modal-content'>
			<div class='modal-header bg-primary'>
				<h5 class='model-title text-light' id='diagTitle'></h5>
				<button type='button'  class='close' data-dismiss='modal'>
          <span>&times;</span>
        </button>
			</div>
			<div class='modal-body'>
				<form>
					<div class='form-group'>
						<label for='formTitle'>Título</label>
						<input type='text' id='formTitle' class='form-control' required>
					</div>
					<div class='form-group'>
						<label for='formDesc'>Descrição</label>
						<textarea id='formDesc' class='form-control'
							placeholder='Breve descrição (opcional)' rows=2>
						</textarea>
					</div>
					<div class='from-group' id='formDataRow'>
						<label for='formDate'>Data de Entrega (Previsão)</label>
						<input type='date' id='formDate' class='form-control' required>
					</div>
				</form>
				<span class='d-none' id='formTipo'></span>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-secondary' data-dismiss='modal'>
					Cancelar
				</button>
        <button type='button' class='btn btn-primary' id='submit'>
					Salvar
				</button>
			</div>
		</div>
	</div>
</div>