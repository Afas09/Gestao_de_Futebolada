<?php
include '../inc/config.php';
include '../inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM tabela WHERE idtabela = ?');
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        exit('Não existe nenhum registo com esse ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM tabela WHERE idtabela = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Registo eleminado com sucesso!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: tabela_read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>


<?=template_header('Tabela :: Eliminar', $project_path)?>

<div class="content delete">
	<h2>Apagar Tabela #<?=$item['idtabela']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <div class="yesno">
        <a href="tabela_read.php">VOLTAR À LISTA</a>
    </div>
    <?php else: ?>
	<p>Tem a certeza que pretende eliminar a Tabela #<?=$item['idtabela']?> - <?=$item['nome']?>?</p>
    <div class="yesno">
        <a href="tabela_delete.php?id=<?=$item['idtabela']?>&confirm=yes">SIM</a>
        <a href="tabela_delete.php?id=<?=$item['idtabela']?>&confirm=no">NÃO</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>