<?php
	require('../contents/config.php'); // Informações de conexão ao SGBD.
	// Recebe as entradas do usuário.
	$titulo = $_POST['titulo'];
	$desc		= $_POST['desc'];
	$data		= $_POST['data'];
	
	// Prepara a resposta. Será um array codificado em JSON.
	// O primeiro elemento é um código numérico, usado para facilitar o tratamento
	// que o JS vai dar ao DOM dependendo do resultado do AJAX.
	// O segundo, é uma reposta textual mais humana, para que o usuário entenda
	// do que se trata o problema.
	$retorno = [];
	
	// Verifica se o título já existe.
	$consulta = $conn->prepare("SELECT count(*) FROM projetos
															WHERE titulo = ?");
	$consulta->bind_param('s', $titulo);
	$consulta->execute();
	$res = $consulta->get_result();
	
	// Se sim, a resposta vai informar a duplicação ao usuário.
	if($res->num_rows > 0) {
		$retorno[] = 0
		$retorno[] = "O título informado já existe. Não são permitidos títulos\
duplicados

	$consulta = $conn->prepare("INSERT INTO projetos (id, titulo, data, desc)
															VALUES (NULL, ?, ?, ?)");



	echo "Dados recebidos\nTitulo: {$titulo}. Desc: {$desc}. Data: {$data}.";
?>