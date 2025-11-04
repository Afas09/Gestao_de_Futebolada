<?php
include '../inc/config.php';
include '../inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM clube WHERE idclube = ?');
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        exit('Não existe nenhum registo com esse ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            /*$stmt = $pdo->prepare('DELETE FROM clube WHERE idclube = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'Registo eleminado com sucesso!';*/
            try {
                $stmt = $pdo->prepare('DELETE FROM clube WHERE idclube = ?');
                $stmt->execute([$_GET['id']]);
                $msg = 'Registo eliminado com sucesso!';
            } catch (PDOException $e) {
                // Verifica se é erro de integridade referencial
                if ($e->getCode() == 23000) {
                    $msg = 'Não é possível eliminar este clube porque existem inscritos associados.';
                } else {
                    $msg = 'Ocorreu um erro ao eliminar o registo: ' . $e->getMessage();
                }
            }  
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: index.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>


<?=template_header('Clube :: Eliminar', $project_path)?>

<div class="content delete">
	<h2>Apagar Clube #<?=$item['idclube']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <div class="yesno">
        <a href="index.php">VOLTAR À LISTA</a>
    </div>
    <?php else: ?>
	<p>Tem a certeza que pretende eliminar o Clube #<?=$item['idclube']?> - <?=$item['nome']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$item['idclube']?>&confirm=yes">SIM</a>
        <a href="delete.php?id=<?=$item['idclube']?>&confirm=no">NÃO</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>