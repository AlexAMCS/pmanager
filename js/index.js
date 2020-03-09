function formSet(elemento) {
	// Edita o formulário modal de acordo com o elemento de origem.
	// Como os formulário de tarefa e projeto, seja de edição ou criação,
	// são exstremamente similares, faz mais sentido manter um formulário só 
	// e editar as etiquetas e outras pecularidades via JS.

	// Flag para o formulário de tarefas (para esconder elementos).
	tflag = 0;
	
	// Flag de edição.
	editFlag = 0;
	
	// Editar projeto.
	if(elemento.matches('.projeto')) {
		editFlag = 1;
		diagTitle = 'Editando Projeto #' + elemento.id;
		
	}
	// Editar tarefa.
	else if(elemento.matches('.tarefa')) {
		editFlag = 1;
		tflag = 1;
		diagTitle = 'Editando Tarefa #' + elemento.id;
	}
	// Criar projeto.
	else if(elemento.matches('#novoProjeto')) {
		diagTitle = 'Criando novo projeto';
	}
	// Criar tarefa.
	else if(elemento.matches('#novaTarefa')) {
		tflag = 1;
		diagTitle = 'Criando nova tarefa'
	}
	else {
		alert('Erro inesperado na criação do formulário');
		return 1;
	}
	
	// Ajustando o formulário.

	$('#formulario #diagTitle').html(diagTitle);
	
	if(editFlag == 1) {
		
			
		}
	}
	//$('#formulario #formTitle').attr('placeholder',valores['tituloPH']);
	return 0;
}

$(function DOMReady() {
	$('.dialog-open').click(function gerarDialog() {
		formSet(this)
	});
	
	
	$('#submit').click(function enviaDados() {
		$.ajax('ajax/ajax.php',{
			data: {
				titulo: $('#formTitle').val(),
				desc: $('#formDesc').val(),
				data: $('#formDate').val(),
				tipo: 1
			},
			method: 'POST',
			success: function retornoAjax(valor, status) {
				// Trata o retorno.
				alert(status);
			}
		});
		
		
		
		
	});
});