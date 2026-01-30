<?php
// Arquivo onde estão cadastrados os candidatos 
$arquivoCandidatos = 'dados/dados.txt';
// Arquivo onde os votos serão gravados
$arquivoVotos = 'dados/votos.txt';
// Variável para armazenar mensagens de erro ou sucesso
$mensagem = "";

// Função de validação do CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = ((10 * $soma) % 11) % 10;
        if ($cpf[$i] != $digito) {
            return false;
        }
    }
    return true;
}
// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Recebe o CPF digitado no formulário
// trim remove espaços antes e depois do que foi digitado
    $cpf = trim($_POST['cpf'] ?? '');

// Recebe o ID do candidato selecionado
    $idCandidato = trim($_POST['candidato'] ?? '');

// Remove pontos e traço do CPF para padronizar
    $cpfLimpo = preg_replace('/\D/', '', $cpf);

    if (empty($cpfLimpo) || empty($idCandidato)) {
        $mensagem = "<span style='color:red;'>Preencha todos os campos.</span>";

    } elseif (!validarCPF($cpfLimpo)) {
        $mensagem = "<span style='color:red;'>CPF inválido.</span>";

    } else {

    // Verifica se o CPF já votou
        if (file_exists($arquivoVotos)) {
            $votos = file($arquivoVotos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($votos as $voto) {
                list($cpfEleitor,) = explode('|', $voto);

                if ($cpfEleitor === $cpfLimpo) {
                    $mensagem = "<span style='color:red;'>Você já votou!</span>";
                    break;
                }
            }
        }

    // Se não houver erro, grava o voto
        if (empty($mensagem)) {
            $linha = $cpfLimpo . "|" . $idCandidato . PHP_EOL;
            file_put_contents($arquivoVotos, $linha, FILE_APPEND | LOCK_EX);

            $mensagem = "<span style='color:green;'>Voto registrado com sucesso!</span>";
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
   <!-- comando abaixo serve para colocar ícone no título -->
<link rel="icon" type="image/png" href="Images/icon1.png">

</head>
<body>
 
<form method="post">
        <h2>Sistema de Votação</h2>
 
 <!-- Campo para digitar o CPF -->
        <label for="cpf">Digite seu CPF (sem pontos) para realizar o voto:  </label> <br>
        
<!-- Aceita somente 11 digitos do CPF  -->
    <input  type="text" name="cpf" placeholder="Somente números" maxlength="11" pattern="\d{11}" inputmode="numeric" required>
 
        <select name="candidato" required>
            <option value="">Escolha o candidato</option>
            <?php
            if (file_exists($arquivoCandidatos)) {

// $linha é o array. Foreach percorre cada linha do arquivo dados.txt

// Essse comando : $linhas = file($arquivoCandidatos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); faz isso:
//   $linhas = [
//   "1|Maria Silva",
//   "2|João Santos",
//   "3|Ana Souza" ];

//O explode() quebra uma string em partes, usando um separador. ex: explode('|', $linha) na pratica: explode('|', "1|Maria Silva");
//O foreach percorre uma linha por vez.
//list($id, $nome) = ...
//O list() pega os valores do array e distribui em variáveis.
//echo "<option value='$id'>$nome</option>";
//<option value="1">Maria Silva</option>

                $linhas = file($arquivoCandidatos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
 

                foreach ($linhas as $linha) {
                    list($id, $nome) = explode('|', $linha);
                   // echo "<option value='$id'>$id - $nome</option>"; --> para exibir o ID e no Nome para o usuário final
                    echo "<option value='$id'>$nome</option>";
                }
            }
            ?>
        </select>
 
     
        <?php
        if ($mensagem) {
            if ($mensagem === "sucesso") {
                echo "<p style='color: green;'>$mensagem</p>";
            } else {
                echo "<p style='color: red;'>$mensagem</p>";
            }
        }
        ?>
 
 
     <button type="submit"> <strong>SALVAR </strong></button>
   
    <a href="index.php">
    <button type="button" class="inicio"> <strong>HOME</strong></button>
    </a>
 
    </form>
 
</body>
</html>