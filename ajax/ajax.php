<?php
	require('../contents/config.php'); // Informações de conexão ao SGBD.
	
	//########################
	//# Definição de funções #
	//########################
	
	function returnCode($code, $msg) {
		// Envia um JSON Array contendo o código de retorno personalizado e uma
		// mensagem de texto explicativa do mesmo.
		$retorno = [$code, $msg];
		echo json_encode($retorno);
		exit();
	}
	
	//##########################
	//# Tratamento de entradas #
	//##########################
	
	// Recebe, sanitiza e valida as entradas do usuário.
	
	// Remove espaços em branco excedentes.
	array_filter($_POST, fn(&$val) => trim($val));   
	
	// Sanitiza os inteiros, e define se é projeto, tarefa, criação/edição ou
	// exclusão.
	$tipo 	= filter_var($_POST['tipo'], FILTER_SANITIZE_NUMBER_INT);
	$id			= filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
	
	// Faz as validações dos inteiros.
	if($tipo = filter_var($tipo, FILTER_VALIDATE_INT)) {
		if(is_int($id = filter_var($id, FILTER_VALIDATE_INT))) {
			if($tipo%2 == 0) {
				$tabela = 'tarefas';
				$colData = '';
				$colDataVal = '';
				$colDataEdit = '';
			}
			else {
				$tabela = 'projetos';
				$colData = '`data`,';
				$colDataVal = '?,';
				$colDataEdit = ',`data` = ?';
			}
		}
		else returnCode(402, "ID de objeto inválido: {$id}");
	}
	else returnCode(401, "Tipo de ação desconhecido: {$tipo}");
	
	// Se for exclusão, basta o id.
	if($tipo > 1) {
		$sql = "DELETE FROM {$tabela} WHERE id = ?";
		
		$consulta = $conn->prepare($sql);
		$consulta->bind_param('i', $id);
		$consulta->execute();
		
		if($consulta->affected_rows > 0){
			returnCode(202, "Projeto #{$id} exlcuído.");
		}
	}
	// Se for inserção ou edição, precisa obter os demais dados.
	if($tipo < 2) {
		// TODO: Sanitizar e validar as variáveis de texto e data. Com prepared
		// statements, já não há risco de SQL injection, pelo menos.
		$titulo = $_POST['titulo'];
		$desc		= $_POST['desc'];
		$data		= $_POST['data'];
		
		// Verifica se o título já existe.
		// Se for inserção, não precisa verificar ids distintos.
		// Se for edição, deve se excluir da busca.
		
		if(!$id) $editCheck = '';
		else $editCheck = "AND id <> ?";
		
		$consulta = $conn->prepare("SELECT count(*) as total FROM projetos
																WHERE titulo = ? {$editCheck}");
		if(!$id) $consulta->bind_param('s', $titulo);
		else $consulta->bind_param('si', $titulo, $id);
		$consulta->execute();
		$res = $consulta->get_result();
		$consulta->close();
		
		// Se sim, a resposta vai informar a duplicação ao usuário.
		if($res->fetch_assoc()['total'] > 0) {
			$retstr = 	"O título informado já existe. ";
			$retstr .= 	"Não são permitidos títulos duplicados.\n";
			$retstr .= 	"Quantidade: {$res->num_rows}";
			returnCode(400, $retstr);
		}
		
		if(!$id) $sql 	= "INSERT INTO {$tabela} (`id`, `titulo`, {$colData} `desc`)
										VALUES (NULL, ?, {$colDataVal} ?)";
		
		else $sql 		= "UPDATE {$tabela}
										SET	`titulo` = ?, `desc` = ? {$colDataEdit}
										WHERE	`id` = ?";
											
		$consulta = $conn->prepare($sql);
		
		if($tipo % 2 == 0) {
			if(!$id) $consulta->bind_param('ss', $titulo, $desc);
			else $consulta->bind_param('ssi', $titulo, $desc, $id);
		}
		else {
			if(!$id) $consulta->bind_param('sss', $titulo, $data, $desc);
			else $consulta->bind_param('sssi', $titulo, $desc, $data, $id);
		}

		$consulta->execute();
		if($consulta->affected_rows > 0){
			$nome = strtoupper($tabela[0]).substr($tabela, 1, strlen($tabela) - 2);
			if(!$id) $acao = 'criad';
			else $acao = 'editad';
			returnCode(200, "{$nome} {$acao}{$nome[-1]}.");
		}
		$consulta->close();
	}
?>