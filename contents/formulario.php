<div class='modal fade' id='formulario'>
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
					<div class='from-group d-none' id='formDateRow'>
						<label for='formDate'>Data de Entrega (Previsão)</label>
						<input type='date' id='formDate' class='form-control'>
					</div>
				</form>
				<span class='d-none' id='formTipo'></span>
				<span class='d-none' id='objId'>0</span>
			</div>
			<div class='modal-footer'>
				<button type='button' class='btn btn-secondary' data-dismiss='modal'>
					Cancelar
				</button>
				<button type='button' class='btn btn-danger d-none ajax' id='delete'>
					Apagar
				</button>
        <button type='button' class='btn btn-primary ajax' id='submit'>
					Criar
				</button>
			</div>
		</div>
	</div>
</div>