<main class='container text-light mt-2' id='listaProjetos'>
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
				<th>Gerenciar Tarefas</th>
				<th>Editar</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while($projeto = $proResp->fetch_assoc()):
					// TODO: Essa query ainda pode ser otimizada para uma só consulta, em
					// casos que haja muitas tarefas ou projetos, ao obter todo o conjunto
					// de tarefas, ou seccionar em partes menores.
					$sql = "SELECT count(*) as total FROM tarefas
									WHERE pid = {$projeto['id']}";
					$tarResp = $conn->query($sql);
					$numTarefas = $tarResp->fetch_assoc()['total'];
			?>
			<tr>
				<td><?= $projeto['id'] ?></td>
				<td><?= $projeto['titulo'] ?></td>
				<td><?= $projeto['data'] ?></td>
				<td><?= $numTarefas ?></td>
				<td class='d-none'><?= $projeto['desc'] ?></td>
				<td class='text-center cursor-pointer tarefas-open' data-toogle='modal'
				data-target='#listaTarefas'>&#x1F4CB;</td>
				<td class='dialog-open cursor-pointer text-center projeto'
					id='<?= $projeto['id'] ?>' data-toggle='modal' data-target='#formulario'>
					
					&#x1F589;
					
				</td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
	<?php require('tarefas.htm'); ?>
<?php endif; ?>
</main>