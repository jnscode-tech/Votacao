<?php
// Arquivo onde estão registrados os votos (cpf|idCandidato)
$arquivoVotos = 'dados/votos.txt';

// Arquivo onde estão cadastrados os candidatos (id|nome)
$arquivoCandidatos = 'dados/dados.txt';

// Verifica se os arquivos existem
if (!file_exists($arquivoVotos) || !file_exists($arquivoCandidatos)) {

    // Caso não exista votação ou candidatos cadastrados
    $mensagem = "Não houve votação até o presente momento.";

} else {

    // Array que vai armazenar os candidatos no formato:
    // [id => nome]
    $candidatos = [];

    // Lê o arquivo de candidatos linha por linha
    $linhasCandidatos = file(
        $arquivoCandidatos,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    // Percorre cada linha do arquivo
    foreach ($linhasCandidatos as $linha) {

        // Separa o id e o nome usando o caractere "|"
        list($id, $nome) = explode('|', $linha);

        // Armazena no array associativo
        $candidatos[$id] = $nome;
    }

// Array que vai armazenar a quantidade de votos por candidato
// Exemplo: [1 => 3, 2 => 5]
    $contagem = [];

// Lê o arquivo de votos
    $linhasVotos = file(
        $arquivoVotos,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

// Percorre cada voto registrado
    foreach ($linhasVotos as $voto) {

// Separa CPF e ID do candidato
// O CPF é ignorado usando a vírgula antes da variável
        list(, $idCandidato) = explode('|', $voto);

// Se ainda não existir esse candidato na contagem,
// inicializa com zero
        if (!isset($contagem[$idCandidato])) {
            $contagem[$idCandidato] = 0;
        }

// Soma 1 voto para o candidato
        $contagem[$idCandidato]++;
    }
// Array final com nome do candidato e quantidade de votos
    $resultados = [];

// Percorre todos os candidatos cadastrados
    foreach ($candidatos as $id => $nome) {

// Cria um array com nome e votos
// Se o candidato não tiver votos, usa 0
        $resultados[] = [
            'nome'  => $nome,
            'votos' => $contagem[$id] ?? 0
        ];
    }


// Ordena o array do maior número de votos para o menor
    usort($resultados, function ($a, $b) {
        return $b['votos'] <=> $a['votos'];
    });
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Define codificação de caracteres -->
    <meta charset="UTF-8">

    <!-- Título da página -->
    <title>Resultado da Votação</title>

    <!-- CSS externo -->
    <link rel="stylesheet" href="Estilizacao/style2.css">

    <!-- Ícone da aba -->
    <link rel="icon" type="image/png" href="Images/icon3.png">
</head>
<body>

<div class="resultado-container">

    <!-- Título principal -->
    <h1>Resultado final</h1>

    <!-- Se existir mensagem, exibe alerta -->
    <?php if (isset($mensagem)): ?>
        <p class="mensagem-alerta"><?= $mensagem ?></p>
    <?php else: ?>

        <!-- Tabela de resultados -->
        <table>
            <tr>
                <th>Candidato</th>
                <th>Votos</th>
            </tr>

            <!-- Percorre os resultados -->
            <?php foreach ($resultados as $resultado): ?>
                <tr>
                    <!-- htmlspecialchars evita problemas de segurança -->
                    <td><?= htmlspecialchars($resultado['nome']) ?></td>

                    <!-- Exibe quantidade de votos -->
                    <td><?= $resultado['votos'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>

    <!-- Botão para voltar à página inicial -->
    <a href="index.php">
        <button type="button" class="inicio-resultado">
            <strong>HOME</strong>
        </button>
    </a>
</div>

</body>
</html>
