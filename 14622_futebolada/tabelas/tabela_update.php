<?php
include '../inc/config.php';
include '../inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {

    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'ok') {
            $msg = 'Registo atualizado com sucesso.';
        }
    }
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';

        // Update the record
        $stmt = $pdo->prepare('UPDATE tabela SET nome = ? WHERE idtabela = ?');
        $stmt->execute([$nome, $_GET['id']]);
        $msg = 'Registo atualizado com sucesso.';

        // redirect para que nao haja 'reenvio' de atualizacao dos dados submetidos pelo formulario
        header("Location: tabela_update.php?id=" . $_GET['id'] . "&status=ok");
        exit;
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM tabela WHERE idtabela = ?');
    $stmt->execute([$_GET['id']]);
    $item_tabela = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$item_tabela) {
        exit('Não encontro nenhum registo com esse ID!');
    }
} else {
    exit('No ID specified!');
}
?>


<?=template_header('Tabelas :: Editar/Ver', $project_path)?>

<div class="content update">
	<h2>Atualizar Tabela #<?=$item_tabela['idtabela']?></h2>
    <form action="tabela_update.php?id=<?=$item_tabela['idtabela']?>" method="post">
        <label for="id">ID</label>
        <label for="nome">Nome da Tabela</label>
        <input type="text" name="id" placeholder="1" value="<?=$item_tabela['idtabela']?>" id="id">
        <input type="text" name="nome" placeholder="Nome da Tabela" value="<?=$item_tabela['nome']?>" id="nome">

        <input type="submit" value="Atualizar">
        <button type="button" class="danger" onclick="javascript:location.href='tabela_read.php'">Cancelar</button>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>