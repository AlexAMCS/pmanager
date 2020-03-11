//########################
//# Definição de funções #
//########################

// TODO: [Importante] Usar variáveis locais ao passar para o modo de produção.
// Refatorar o fromSet pra enviar a flag de projeto.

function formSet(element) {
	// Edita o formulário modal de acordo com o elemento de origem.
	// Como os formulário de tarefa e projeto, seja de edição ou criação,
	// são extremamente similares, faz mais sentido manter um formulário só 
	// e editar as etiquetas e outras pecularidades via JS.

	// Flag que determina o tipo de objeto sendo modificado (Projeto/Tarefa).
	projectFlag = 0;
	
	// Flag de edição. Muda o texto do botão de salvar, e identifica o id
	// do projeto.
	editFlag = 0;
	
	// TODO: [Simplificação]{A decidir} Combinar flags.

	// Editar projeto.
	if(element.matches('.project')) {
		projectFlag = 1
		editFlag = element.id;
		dialogTitle = 'Editando Projeto #' + editFlag;
		
	}
	// Editar tarefa.
	else if(element.matches('.task')) {
		taskflag = 1;
		editFlag = element.id;
		dialogTitle = 'Editando Tarefa #' + editFlag;
	}
	// Criar projeto.
	else if(element.matches('#newProject')) {
		projectFlag = 1
		dialogTitle = 'Criando novo projeto';
	}
	// Criar tarefa.
	else if(element.matches('#newTask')) {
		taskflag = 1;
		dialogTitle = 'Criando nova tarefa';
	}
	else {
		alert('Erro inesperado na criação do formulário');
		return 1;
	}
	
	//##########################
	//# Ajustando o formulário #
	//##########################
	
	// Título do dialog
	$('#form #dialogTitle').html(dialogTitle);
	
	// Tipo.
	$('#formType').html(projectFlag);
	
	// Ajusta botões e insere dados de edição.
	if(editFlag)	{
		// Dados
		$('#objId').html(editFlag);
		$('#formTitle').val(element.parentNode.children[1].innerHTML);
		$('#formDesc').val(element.parentNode.children[2].innerHTML);
		if(projectFlag) $('#formDate').val(element.parentNode.children[3].innerHTML);

		
		// Botões
		$('#submit').html('Salvar');
		$('#delete').removeClass('d-none');
	}
	else 	{
		$('#objId').html(0);
		$('#formTitle').val('');
		if(projectFlag) $('#formDate').val('');
		$('#formDesc').val('');
		
		$('#submit').html('Criar');
		$('#delete').addClass('d-none');
	}
	
	// Linha de data.
	if(projectFlag) {
		$('#formDateRow').removeClass('d-none');
		$('#formDate').attr('required','');
	}
	else $('#formDateRow').addClass('d-none');
	
	return 0;
}
function loadTaskList(element) {
	// Carrega a lista de tarefas associadas ao projeto em questão via AJAX.
	id = element.parentNode.children[0].innerHTML;
	
	$.ajax('contents/taskList.php',{
		data: { id: id },
		method: 'POST',
		success: function(resp) {
			// Remove a lista de projetos.
			$('#projectList').remove();
			
			// Insere a lista de tarefas.
			$(resp).insertAfter('header');
			
		}
	}).then(function(){
		// Adiciona o botão de Nova Tarefa no navbar, usando Template Strings.
		add = `<li class='nav-item'>
						<span class='nav-link cursor-pointer dialog-open'id='newTask'
							data-toggle='modal' data-target='#form'>
							Nova Tarefa
						</span>
					</li>`;
		$(add).insertAfter($('#newProject').parent());
				
		// Adiciona os eventListeners.
		$('.dialog-open').click(function(){
			formSet(this);
		});
	});
}


function loadProjectList() {
	// Carrega a lista de projetos, em seguida insere os eventListeners via
	// Promise nos elementos inseridos.
	$.ajax('contents/projectList.php',{
			method: 'GET',
			success: function(resp) {
				$(resp).insertAfter('header');
			}
	}).then(function() {
		$('.dialog-open').click(function(){
			formSet(this);
		});
		$('.task-open').click(function(){
			loadTaskList(this);
		});
		
	});
}

//####################
//# Página carregada # 
//####################

$(function DOMReady() {
	// Adiciona o eventListener ao link de criação de projetos.
	$('#newProject').click(function() {
		formSet(this)
	});
	
	// Se fechar o modal de criar projeto na página de tarefas, recarrega.
	/*$('#form').on('hide.bs.modal', function() {
		window.location.reload(true);
	});
	*/
	// Carrega via AJAX a lista de tarefas e aplica eventos onClick via Promise.
	loadProjectList();
	
	
	// AJAX de modificações no SGBD.
	$('.ajax').click(function() {
		if(this.matches('#submit')) {
			dataset = {
				id:			$('#objId').html(),
				title:	$('#formTitle').val(),
				desc: 	$('#formDesc').val(),
				date: 	$('#formDate').val(),
				type: 	$('#formType').html()
			}
			if(!(dataset['type'] % 2)) {
				dataset = {
					...dataset,
					pid: $('#pid').html()
				}
			}
		}
		else dataset = { 
			id: $('#objId').html(),
			type: parseInt($('#formType').html(),10) + 2
		}
		
		$.ajax('contents/sql.php',{
			data: dataset,
			method: 'POST',
			success: function(respArray) {
				// Retorno vem em JSON.
				resp = JSON.parse(respArray)
				
				// Retorna para o usuário alguma mensagem.
				alert(resp[1]);
				
				// Se foi uma resposta válida, fecha o modal, remove e recarrega a
				// lista de tarefas.
				// TODO: [Otimização] Recarregar apenas o elemento alterado, se a lista
				// atual não for vazia.
				if(resp[0] >= 200 && resp[0] < 300) {
					window.location.reload(true);
				}
			}
		});
	});
});