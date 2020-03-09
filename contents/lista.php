<main class='container text-light mt-2'>
<?php
	$sql = "SELECT * FROM projetos
					ORDER BY data DESC";
	$proResp = $conn->query($sql);
	
	if($proResp->num_rows == 0): require('vazio.htm');
	else:
?>
	<table class='table table-dark table-striped table-bordered table-hover'>
		<thead class='bg-primary'>
			<tr>
				<th>ID</th>
				<th>Título</th>
				<th>Data Prevista de Entrega</th>
				<th>Número de Tarefas Cadastradas</th>
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
			<tr class='projeto dialog-open' id='<?= $projeto['id'] ?>'
				data-toggle='modal'	data-target='#formulario'>

				<td><?= $projeto['id'] ?></td>
				<td><?= $projeto['titulo'] ?></td>
				<td><?= $projeto['data'] ?></td>
				<td><?= $numTarefas ?></td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
<?php endif; ?>
</main>