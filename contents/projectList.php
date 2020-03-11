<main class='container text-light mt-2' id='projectList'>
<?php
	require('config.php'); // Informações de conexão ao SGBD.
	$sql = "SELECT * FROM projetos
					ORDER BY data ASC";
	$proResp = $conn->query($sql);
	
	// TODO: [Multi-Usuário]{A decidir} Implementar o reload como um SSE.
	
	if($proResp->num_rows == 0): ?>
		<span>
			<h2>Não há projetos cadastrados.</h2><br>
			<h5>
				Utilize o botão na barra de navegação acima para criar um novo projeto.
			</h5>
		</span>
	</main>
<?php	
	else:
?>
	<h2>Lista de Projetos</h2>
	<table class='table table-dark table-striped table-bordered table-hover'>
		<thead class='bg-primary'>
			<tr>
				<th>ID</th>
				<th>Título</th>
				<th class='d-none'>Descrição</th>
				<th>Data Prevista</th>
				<th>Tarefas Cadastradas</th>
				<th>Gerenciar Tarefas</th>
				<th>Editar/Apagar</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while($project = $proResp->fetch_assoc()):
					// TODO: [Otimização] Essa query ainda pode ser otimizada para uma só
					// consulta, em casos que haja muitas tarefas ou projetos, ao obter
					// todo o conjunto de tarefas, ou seccionar em partes menores.
					$sql = "SELECT count(*) as total FROM tarefas
									WHERE pid = {$project['id']}";
					$taskCountResp = $conn->query($sql);
					$taskCount = $taskCountResp->fetch_assoc()['total'];
			?>
			<tr>
				<td><?= $project['id'] ?></td>
				<td><?= $project['titulo'] ?></td>
				<td><?= $project['data'] ?></td>
				<td><?= $taskCount ?></td>
				<td class='d-none'><?= $project['desc'] ?></td>
				<td class='text-center cursor-pointer task-open'>&#x1F4CB;</td>
				<td class='dialog-open cursor-pointer text-center project'
					id='<?= $project['id'] ?>' data-toggle='modal' data-target='#form'>
					&#x1F589;
				</td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php endif; ?>
</main>