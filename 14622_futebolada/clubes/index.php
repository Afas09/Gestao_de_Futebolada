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
$stmt = $pdo->prepare('SELECT * FROM clube ORDER BY idclube LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$lista_registos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_registos = $pdo->query('SELECT COUNT(*) FROM clube')->fetchColumn();
$pag_begin = ($page - 1) * $records_per_page + 1;
$pag_end = $page * $records_per_page;
if ($pag_end > $num_registos) $pag_end = $num_registos;
?>


<?=template_header('Clube : Lista', $project_path)?>

<div class="content read">
	<h2>Clube : Lista</h2>
	<a href="create.php" class="create-contact">Criar Clube</a>

    <?php if ($num_registos>0) {?>
        <p>A mostrar <?php echo ($pag_begin). " a " . $pag_end; ?> num total de <?php echo $num_registos; ?> registos</p>
    <?php }?>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Nome do Clube</td>
                <td>Cor do Clube</td>
                <td>Hino do Clube</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista_registos as $item): ?>
            <tr>
                <td><?=$item['idclube']?></td>
                <td><?=$item['nome']?></td>
                <td style="background: <?=$item['cor']?>"></td>
                <td><?=str_replace("\n", "<br>", $item['hino']);?></td>
                <td class="actions">
                    <a href="update.php?id=<?=$item['idclube']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$item['idclube']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="index.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_registos): ?>
		<a href="index.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>