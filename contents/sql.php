<?php
	// Informações de conexão ao SGBD e funções de retorno.
	require('../contents/config.php'); 
	
	//##########################
	//# Tratamento de entradas #
	//##########################
	
	// Recebe, sanitiza e valida as entradas do usuário.
	
	// Remove espaços em branco excedentes.
	array_filter($_POST, fn(&$val) => trim($val));   
	
	// Sanitiza os inteiros.
	// A variável type define se o objeto manipulado é projeto ou tarefa,
	// e se é exclusão ou edição/inclusão.
	// A variável id define o id do objeto a ser editado/excluído.
	// Se for 0, é inclusão.
	//
	// Sumário.
	//
	//	$type == 0 	- Tarefa 	(update/insert)
	//	$type == 1	-	Projeto (update/insert)
	//	$type == 2	-	Tarefa 	(delete)
	//	$type == 3	-	Projeto (delete)
	//
	//	$id == 0	- Update
	//	$id > 0 	- Insert
	
	$type 	= filter_var($_POST['type'], FILTER_SANITIZE_NUMBER_INT);
	$id			= filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_POST['pid'])) {
		$pid	= filter_var($_POST['pid'], FILTER_SANITIZE_NUMBER_INT);
	}
	// Faz as validações dos inteiros.
	if(is_int($type = filter_var($type, FILTER_VALIDATE_INT))) {
		if(is_int($id = filter_var($id, FILTER_VALIDATE_INT))) {
			if($type%2 == 0) {
				$tableName = 'tarefas';
				$column = 'pid,';
				$colDataEdit = '';
			}
			else {
				$tableName = 'projetos';
				$column = '`data`,';
				$colDataEdit = ',`data` = ?';
			}
		}
		else returnCode(402, "ID de objeto inválido: {$id}");
	}
	else returnCode(401, "Tipo de ação desconhecido: {$type}");
	
	// Variável para determiniar, nas mensagens de resposta, qual foi o tipo de
	// objeto manipulado.
	$name = 	strtoupper($tableName[0]);
	$name .= 	substr($tableName, 1, strlen($tableName) - 2);
		
	// Se for exclusão, basta o id.
	if($type > 1) {
		$sql = "DELETE FROM {$tableName} WHERE id = ?";
		$query = $conn->prepare($sql);
		$query->bind_param('i', $id);
		$query->execute();
		
		if($query->affected_rows > 0){
			returnCode(202, "{$name} #{$id} exlcuíd{$name[-1]}.");
		}
	}
	
	// Se for inserção ou edição, precisa obter os demais dados.
	if($type < 2) {
		// TODO: Sanitizar e validar as variáveis de texto e data. Com prepared
		// statements, já não há risco de SQL injection, pelo menos.
		$title 	= $_POST['title'];
		$desc		= $_POST['desc'];
		$date		= $_POST['date'];
		
		// Verifica se o título já existe.
		// Não faz diferenciação de case (fácil de implementar com o UPPER, mas não
		// é requisito.
		// Se for inserção, não precisa verificar ids distintos.
		// Se for edição, deve se excluir o objeto em questão da busca.
		// Se for tarefa, deve limitar a busca a projetos de mesmo id.
		
		// Precisamos do ID de projeto associado ao id da tarefa.
		// TODO: [Otimização] Passar como parâmetro para esse script.

		if(!$id) $editCheck = '';
		else 	$editCheck = 'AND id <> ?';
		if($type%2 == 0) $editCheck .= " AND pid = {$pid}";
		
		$query = $conn->prepare("SELECT count(*) as total FROM {$tableName}
														 WHERE titulo = ? {$editCheck}");
		if(!$id) $query->bind_param('s', $title);
		else $query->bind_param('si', $title, $id);
		$query->execute();
		
		$resp = $query->get_result();
		$query->close();
		
		if($resp->num_rows > 0){
			// Se sim, a resposta vai informar a duplicação ao usuário.
			if($resp->fetch_assoc()['total'] > 0) {
				$retstr = 	"O título informado já existe.\n";
				$retstr .= 	"Não são permitidos títulos duplicados.";
				returnCode(400, $retstr);
			}
		}
	
		if(!$id) $sql 	= "INSERT INTO {$tableName} (`id`, `titulo`, {$column} `desc`)
											 VALUES (NULL, ?, ?, ?)";
		
		else $sql 		= "UPDATE {$tableName}
										 SET	`titulo` = ?, `desc` = ? {$colDataEdit}
										 WHERE	`id` = ?";
											
		$query = $conn->prepare($sql);
		
		if($type % 2 == 0) {
			if(!$id) $query->bind_param('sss', $title, $pid, $desc);
			else $query->bind_param('ssi', $title, $desc, $id);
		}
		else {
			if(!$id) $query->bind_param('sss', $title, $date, $desc);
			else $query->bind_param('sssi', $title, $desc, $date, $id);
		}

		$query->execute();
		
		if($query->affected_rows > 0){
			if(!$id) $action = 'criad';
			else $action = 'editad';
			returnCode(200, "{$name} {$action}{$name[-1]}.");
		}
		else {
			if($debug) {
				$error = $query->error;
				returnCode(500,"Erro mySQL: {$error}");
			}
			else {
				returnCode(500,"Erro interno do servidor");
			}
		}
		$query->close();
	}
?>