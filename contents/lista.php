<main class='container text-light mt-2' id='lista'>
<?php
	require('config.php'); // Informações de conexão ao SGBD.
	$sql = "SELECT * FROM projetos
					ORDER BY data ASC";
	$proResp = $conn->query($sql);
	
	// TODO: Implementar o reload como SSE.
	
	if($proResp->num_rows == 0): require('vazio.htm');
	else:
?>
	<table class='table table-dark table-striped table-bordered table-hover'>
		<thead class='bg-primary'>
			<tr>
				<th>ID</th>
				<th>Título</th>
				<th>Data Prevista</th>
				<th>Tarefas Cadastradas</th>
				<th class='d-none'>Descrição</th>
				<th>Adicionar Tarefa</th>
				<th>Editar</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while($projeto = $proResp->fetch_assoc()):
					// Otimizável para uma só consulta, em casos que haja muitas tarefas
					// ou projetos.
					$sql = "SELECT count(*) as total FROM tarefas
									WHERE pid = {$projeto['id']}";
					$tarResp = $conn->query($sql);
					$numTarefas = $tarResp->fetch_assoc()['total'];
			?>
			<tr class='projeto' id='<?= $projeto['id'] ?>'>
				<td><?= $projeto['id'] ?></td>
				<td><?= $projeto['titulo'] ?></td>
				<td><?= $projeto['data'] ?></td>
				<td><?= $numTarefas ?></td>
				<td class='d-none'><?= $projeto['desc'] ?></td>
				<td class='text-center'>&#x1F4CB;</td>
				<td class='dialog-open cursor-pointer text-center' data-toggle='modal'
					data-target='#formulario'>
					
					&#x1F589;
					
				</td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php endif; ?>
</main>