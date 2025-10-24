<?php
include '../inc/config.php';
include '../inc/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
//$records_per_page = 5;

// Prepare the SQL statement and get records from our 'tabela' table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM tabela ORDER BY idtabela LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$lista_tabelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_tabelas = $pdo->query('SELECT COUNT(*) FROM tabela')->fetchColumn();
$pag_begin = ($page - 1) * $records_per_page + 1;
$pag_end = $page * $records_per_page;
if ($pag_end > $num_tabelas) $pag_end = $num_tabelas;
?>


<?=template_header('Tabelas : Lista', $project_path)?>

<div class="content read">
	<h2>Tabela : Lista</h2>
	<a href="tabela_create.php" class="create-contact">Criar tabela</a>
    <p>A mostrar <?php echo ($pag_begin). " a " . $pag_end; ?> num total de <?php echo $num_tabelas; ?> registos</p>

	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Nome da tabela</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_tabelas as $item): ?>
            <tr>
                <td><?=$item['idtabela']?></td>
                <td><?=$item['nome']?></td>
                <td class="actions">
                    <a href="tabela_update.php?id=<?=$item['idtabela']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="tabela_delete.php?id=<?=$item['idtabela']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="tabela_read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_tabelas): ?>
		<a href="tabela_read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>