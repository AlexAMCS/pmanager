<?php
	//########################
	//# Definição de funções #
	//########################
	
	function returnCode($code, $msg) {
		// Envia um JSON Array contendo o código de retorno personalizado e uma
		// mensagem de texto explicativa do mesmo.
		$retarray = [$code, $msg];
		echo json_encode($retarray);
		exit();
	}
	
	//########################
	//# Variáveis de Conexão #
	//########################
	
	$host = 'localhost';
	$port = '3307';
	$db 	= 'pmanager';
	$user = 'pmanager';
	$pass = 'senhaprojeto';
	
	$conn = new mysqli($host.':'.$port, $user, $pass, $db);
	$conn->set_charset('utf8mb4');
?>