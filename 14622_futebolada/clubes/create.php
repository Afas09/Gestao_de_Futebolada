<?php
include '../inc/config.php';
include '../inc/functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record

    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cor = isset($_POST['cor']) ? $_POST['cor'] : '';
    $hino = isset($_POST['hino']) ? $_POST['hino'] : '';
    if ($nome != '' && $cor != '' && $hino != '') {
        // Insert new record into the contacts table
        $stmt = $pdo->prepare('INSERT INTO clube (nome, cor, hino) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $cor, $hino]);

        header("Location: create_ok.php");
        exit;

        // Output message
        $msg = 'Criado com sucesso!';
    } else {
        $msg = 'Tem de ter dados para inserir!';
    }
}
?>


<?=template_header('Clube : Inserir', $project_path)?>

<div class="content update">
	<h2>Clube : Inserir</h2>
    <form action="?op=save" method="post">

        <label for="nome">Nome do Clube</label>
        <label for="cor">Cor</label>
        <input maxlenght=100 type="text" required name="nome" placeholder="Escreva o nome do Clube" id="nome">
        <input maxlenght=50 type="color" required name="cor" placeholder="Escreva a cor do Clube" id="cor">

        <label for="hino">Hino do Clube</label>
        <label for="xxx">&nbsp;</label>
        <textarea style="width:100%;" rows=10 required name="hino" placeholder="Escreva o hino do clube" id="hino"></textarea>

        <input type="submit" value="Inserir">
        <button type="button" class="danger" onclick="javascript:location.href='index.php'">Cancelar</button>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>