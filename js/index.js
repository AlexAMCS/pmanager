function formSet(elemento) {
	// Edita o formulário modal de acordo com o elemento de origem.
	// Como os formulário de tarefa e projeto, seja de edição ou criação,
	// são extremamente similares, faz mais sentido manter um formulário só 
	// e editar as etiquetas e outras pecularidades via JS.

	// Flag que determina o tipo de objeto sendo modificado (Projeto/Tarefa).
	pFlag = 0;
	
	// Flag de edição. Muda o texto do botão de salvar, e identifica o id
	// do projeto.
	editFlag = 0;
	
	// TODO: Combinar flags.

	// Editar projeto.
	if(elemento.matches('.projeto')) {
		pFlag = 1
		editFlag = elemento.id;
		diagTitle = 'Editando Projeto #' + editFlag;
		
	}
	// Editar tarefa.
	else if(elemento.matches('.tarefa')) {
		tflag = 1;
		editFlag = elemento.id;
		diagTitle = 'Editando Tarefa #' + editFlag;
	}
	// Criar projeto.
	else if(elemento.matches('#novoProjeto')) {
		pFlag = 1
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
	
	//##########################
	//# Ajustando o formulário #
	//##########################
	
	// Título do dialog
	$('#formulario #diagTitle').html(diagTitle);
	
	// Tipo.
	$('#formTipo').html(pFlag);
	
	// Ajusta botões e insere dados de edição.
	if(editFlag)	{
		// Dados
		$('#objId').html(editFlag);
		$('#formTitle').val(elemento.parentNode.children[1].innerHTML);
		if(pFlag) $('#formDate').val(elemento.parentNode.children[2].innerHTML);
		$('#formDesc').val(elemento.parentNode.children[4].innerHTML);

		
		// Botões
		$('#submit').html('Salvar');
		$('#delete').removeClass('d-none');
	}
	else 	{
		$('#objId').html(0);
		$('#formTitle').val('');
		if(pFlag) $('#formDate').val('');
		$('#formDesc').val('');
		
		$('#submit').html('Criar');
		$('#delete').addClass('d-none');
		
	}
	
	// Linha de data.
	if(pFlag == 1) {
		$('#formDateRow').removeClass('d-none');
		$('#formDate').attr('required','');
	}
	
	if(pFlag == 0) $('#formDateRow').addClass('d-none');
	
	return 0;
}

function loadProjectList() {
	$.ajax('contents/lista.php',{
			method: 'GET',
			success: function retornoAjax(ret) {
				$(ret).insertAfter('header'); // Trata o retorno.
			}
	}).then(function afterLoad() {
		$('.dialog-open').click(function gerarDialog() {
			formSet(this);
		});
	});
}

function loadTaskList() {
	
	
}

$(function DOMReady() {
	// Carrega via AJAX a lista de tarefas e aplica eventos onClick via Promise.
	loadProjectList();
	
	// Carregando lista de tarefas.
	loadTaskList();
	
	// AJAX de modificações no SGBD.
	$('.ajax').click(function enviaDados() {
		if(this.matches('#submit')) {
			dados = {
				id:			$('#objId').html(),
				titulo: $('#formTitle').val(),
				desc: 	$('#formDesc').val(),
				data: 	$('#formDate').val(),
				tipo: 	$('#formTipo').html()
			}
		}
		else dados = { 
			id: $('#objId').html(),
			tipo: parseInt($('#formTipo').html(),10) + 2
		}
		
		$.ajax('ajax/ajax.php',{
			data: dados,
			method: 'POST',
			success: function retornoAjax(ret) {
				resposta = JSON.parse(ret) // Trata o retorno.
				// Retorna para o usuário alguma mensagem.
				alert(resposta[1]);
				// Se foi uma resposta válida, fecha o modal, remove e recarrega a
				// lista. De tarefas. Otimizável para recarregar apenas o elemento 
				// alterado, se a lista atual não for vazia.
				if(resposta[0] >= 200 && resposta[0] < 300) {
					$('#formulario').modal('hide');
					$('#listaProjetos').remove();
					loadProjectList();
				}
			}
		});
		
			
	});
});