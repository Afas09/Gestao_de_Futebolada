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
        $cor = isset($_POST['cor']) ? $_POST['cor'] : '';
        $hino = isset($_POST['hino']) ? $_POST['hino'] : '';

        // Update the record
        $stmt = $pdo->prepare('UPDATE clube SET nome = ? WHERE idclube = ?');
        $stmt->execute([$nome, $cor, $hino, $_GET['id']]);
        $msg = 'Registo atualizado com sucesso.';

        // redirect para que nao haja 'reenvio' de atualizacao dos dados submetidos pelo formulario
        header("Location: update.php?id=" . $_GET['id'] . "&status=ok");
        exit;
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM clube WHERE idclube = ?');
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$item) {
        exit('Não encontro nenhum registo com esse ID!');
    }
} else {
    exit('No ID specified!');
}
?>


<?=template_header('Clube :: Editar/Ver', $project_path)?>

<div class="content update">
	<h2>Atualizar Clube #<?=$item['idclube']?></h2>
    <form action="tabela_update.php?id=<?=$item['idclube']?>" method="post">
        <label for="id">ID</label>

        <label for="nome">Nome do Clube</label>
        <label for="cor">Cor</label>
        <input maxlenght=100 type="text" required name="nome" placeholder="Escreva o nome do Clube" id="nome">
        <input maxlenght=50 type="color" required name="cor" placeholder="Escreva a cor do Clube" id="cor">

        <label for="hino">Hino do Clube</label>
        <label for="xxx">&nbsp;</label>
        <textarea style="width:100%;" rows=10 required name="hino" placeholder="Escreva o hino do clube" id="hino"></textarea>

        <input type="submit" value="Atualizar">
        <button type="button" class="danger" onclick="javascript:location.href='tabela_read.php'">Cancelar</button>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>