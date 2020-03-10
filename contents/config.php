<?php
	$host = 'localhost';
	$port = '3308';
	$db = 'pmanager';
	$user = 'pmanager';
	$pass = 'senhaprojeto';
	
	$conn = new mysqli($host.':'.$port, $user, $pass, $db);
	$conn->set_charset('utf8mb4');
?>