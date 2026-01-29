<?php
$arquivoCandidatos = 'dados/dados.txt';
$arquivoVotos = 'dados/votos.txt';
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cpf = $_POST['cpf'];
    $idCandidato = $_POST['candidato'];

    if (empty($cpf) || empty($idCandidato)) {
        $mensagem = "Preencha todos os campos.";
    } else {

        // Verifica se o CPF do eleitor já votou
        if (file_exists($arquivoVotos)) {
            $votos = file($arquivoVotos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($votos as $voto) {
                list($cpfEleitor,) = explode('|', $voto);

                if (strcasecmp($cpfEleitor, $cpf) === 0) {
                    $mensagem = "Você já votou!";
                    $mensagem = "<span style='color: red;'>Você já votou!</span>";

                    break;
                }
            }
        }

        // Se ainda não votou, grava o voto
        if (empty($mensagem)) {
            $linha = $cpf . "|" . $idCandidato . PHP_EOL;
            file_put_contents($arquivoVotos, $linha, FILE_APPEND | LOCK_EX);
            $mensagem = "<span style='color: green;'>Voto registrado com sucesso!</span>";

        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Votação</title>
 <link rel="stylesheet" href="Estilizacao/style2.css">
</head>
<body>

<form method="post">
    <h2> Sistema de Votação</h2>

  <!-- Campo para digitar o CPF -->
<input type="number" name="cpf" placeholder="XXX.XXX.XXX-XX" maxlength="11" pattern="\d{3}[\.]?\d{3}[\.]?\d{3}[-]?\d{2}" required>

    <select name="candidato">
        <option value="">Escolha o candidato</option>
        <?php
        if (file_exists($arquivoCandidatos)) {
            $linhas = file($arquivoCandidatos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($linhas as $linha) {
                list($id, $nome) = explode('|', $linha);
                echo "<option value='$id'>$nome</option>";
            }
        }
        ?>
    </select>

    <button type="submit">VOTAR</button>

    <?php
    if ($mensagem) {
        $classe = ($mensagem == "Voto registrado com sucesso!") ? "sucesso" : "erro";
        echo "<p class='$classe'>$mensagem</p>";
    }
    ?>
    <a href="index.php">
    <button type="button" class="inicio"> <strong>HOME</strong></button>
    </a>


</form>

</body>
</html>
