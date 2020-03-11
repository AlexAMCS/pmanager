<main class='container text-light mt-2' id='taskList'>
<?php
	// Informações de conexão ao SGBD e funções de retorno.
	require('config.php'); 
	
	// Sanitiza e valida a entrada de ID de Tarefa.
	$id 	= filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
	if($id = filter_var($id, FILTER_VALIDATE_INT)) {
		$sql = "SELECT * FROM tarefas
						WHERE pid = ?";
		$query = $conn->prepare($sql);
		$query->bind_param('i', $id);
		$query->execute();
		$taskResp = $query->get_result();
	}
	else {
		returnCode(403, "ID de Projeto inválida: {$id}");
	}
		// TODO: Implementar o reload como SSE.
	echo "<span class='d-none' id='pid'>{$id}</span>";
	if($taskResp->num_rows == 0): ?>
		<h5>Não há tarefas cadastradas para esse projeto.</h5>
		<button type='button' class='btn btn-primary dialog-open' id='newTask'
			data-toggle='modal' data-target='#form'>
			Nova Tarefa
		</button>
<?php	
	else:
?>
	<h2>Projeto <?= $id ?> - Lista de Tarefas</h2>
	<table class='table table-dark table-striped table-bordered table-hover'>
		<thead class='bg-primary'>
			<tr>
				<th>ID</th>
				<th>Título</th>
				<th class='d-none'>Descrição</th>
				<th>Editar/Apagar</th>
			</tr>
		</thead>
		<tbody>
			<?php	
				while($task = $taskResp->fetch_assoc()):
			?>
				<tr>
				<td><?= $task['id'] ?></td>
				<td><?= $task['titulo'] ?></td>
				<td class='d-none'><?= $task['desc'] ?></td>
				<td class='dialog-open cursor-pointer text-center task'
					id='<?= $task['id'] ?>' data-toggle='modal' data-target='#form'>
					&#x1F589;
				</td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php endif; ?>
</main>