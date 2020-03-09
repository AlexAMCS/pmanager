<?php
	// Definiindo funções de depuração.
	function consoleDump($obj) {
		ob_start();
		var_dump($obj);
		$res = addslashes(ob_get_contents());
		ob_end_clean();
		echo "<script> console.log('{$res}'); </script>";
	}

?>