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
        $idclube = $_GET['id'];

        if ($nome != '' && $cor != '' && $hino != '') {
            // Update the record
            $stmt = $pdo->prepare('UPDATE clube SET nome = ?, cor = ?, hino = ? WHERE idclube = ?');
            $stmt->execute([$nome, $cor, $hino, $idclube]);
            $msg = 'Registo atualizado com sucesso.';

            // guardar os dados de classificado_em
            $idtabela = isset($_POST['idtabela']) ? intval($_POST['idtabela']) : 0;
            if ($idtabela > 0) {
                // inserir/atualizar relação em 'classificado_em'
                // verificar se existe relação
                // se existir .. atualizar ..
                // se não existir .. criar
                $staux = $pdo->prepare('SELECT * FROM classificado_em WHERE idclube=?;');
                $staux->execute([$_GET['id']]);
                $checkaux = $staux->fetch(PDO::FETCH_ASSOC);
                if (!$checkaux) {
                    // ainda não existe
                    $sqlins = "INSERT INTO classificado_em (idtabela, idclube, nome) VALUES (?, ?, ?);";
                    $stmt = $pdo->prepare($sqlins);
                    $stmt->execute([$idtabela, $idclube, '']);
                } else {
                    // já existe .. atualizar
                    $sqlupdate = "UPDATE classificado_em SET idtabela=? WHERE idclube=? AND idtabela=?;";
                    $stmt = $pdo->prepare($sqlupdate);
                    $stmt->execute([$idtabela, $idclube, $checkaux['idtabela']]);
                }

            } else {
                // eliminar/retirar relação em 'classificado_em'
                $stmt = $pdo->prepare('DELETE FROM classificado_em WHERE idclube = ?');
                $stmt->execute([$idclube]);
            }

            // redirect para que nao haja 'reenvio' de atualizacao dos dados submetidos pelo formulario
            header("Location: update.php?id=" . $_GET['id'] . "&status=ok");
            exit;
        }
    }
    // Get the contact from the contacts table
    $sqlQuery = 'SELECT clube.*,
                        classificado_em.idtabela,
                        classificado_em.lugares,
                        classificado_em.pontos,
                        classificado_em.vitorias,
                        classificado_em.empates,
                        classificado_em.derrotas,
                        classificado_em.golosmarcados,
                        classificado_em.golossofridos,
                        classificado_em.saldogolo
                    FROM
                        clube
                        LEFT JOIN
                        classificado_em 
                            ON clube.idclube=classificado_em.idclube
                    WHERE clube.idclube = ?';

    $stmt = $pdo->prepare($sqlQuery);
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
    <form action="update.php?id=<?=$item['idclube']?>" method="post">

        <label for="nome">ID</label>
        <label for="nome">Nome do Clube</label>
        <input type="text" name="id" placeholder="1" value="<?=$item['idclube']?>" id="id">
        <input maxlenght=100 type="text" required name="nome" placeholder="Escreva o nome do Clube" value="<?=$item['nome']?>" id="nome">

        <label for="cor">Cor</label>
        <label for="xxx">&nbsp;</label>
        <input maxlenght=50 type="color" required name="cor" placeholder="Escreva a cor do Clube" value="<?=$item['cor']?>" id="cor">
        <label for="xxx">&nbsp;</label>

        <label for="hino">Hino do Clube</label>
        <textarea style="width:100%;" rows=10 required name="hino" placeholder="Escreva o hino do clube" id="hino"><?=$item['hino']?></textarea>

        <div style="width:100%">
            <label for="idtabela">Pertence à Tabela: </label>
            <br>
            <select name="idtabela" id="idtabela">
                <option value="0">- sem tabela associada -</option>
                <?php
                $staux = $pdo->prepare('SELECT * FROM tabela ORDER BY idtabela');
                $staux->execute();
                $lista_tabelas = $staux->fetchAll(PDO::FETCH_ASSOC);
                foreach ($lista_tabelas as $ficha_tabela):
                    $chk = "";
                    if ($ficha_tabela['idtabela'] == $item['idtabela']) {
                        $chk = " selected=selected ";
                    }
                    echo '<option ' . $chk . ' value="' . $ficha_tabela['idtabela'] . '">' . $ficha_tabela['nome'] . '</option>';
                endforeach;
                ?>
            </select>
        </div>


        <input type="submit" value="Atualizar">
        <button type="button" class="danger" onclick="javascript:location.href='index.php'">Cancelar</button>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>