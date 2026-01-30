<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Pegar os dados nome, turma e curso do candidato
    $nome  = $_POST["nome"];
    $turma = $_POST["turma"];
    $curso = $_POST["curso"];

// O if verifica se os campos: nome, turma e curso estão vazios, caso esteja ele dá mensagem e não permite salvar dados

    if (!empty($nome) && !empty($turma) && !empty($curso)) {

// variavel dadosCandidatos, armazena as informações no dados.txt

        // Foi criado uma pasta chamada Dados pois não estava funcionando jogar o arquivo dados.txt direto no diretório. 
        //Foi necessário dar permissão de ecrita e leitura na pasta para que o arquivo txt fosse criado  
        $dadosCandidatos = __DIR__ . "/Dados/dados.txt";


// GERA ID SEQUENCIAL  - 1,2, 3....
        if (file_exists($dadosCandidatos)) {
            $linhas = file($dadosCandidatos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $id = count($linhas) + 1;
        } else {
            $id = 1;
        }
//a forma como os dados serão armazenados no bloco de notas: ID: 1 | Nome: Juliana | Turma: Noite | Curso: ADS
        $linha = "ID: $id | Nome: $nome | Turma: $turma | Curso: $curso" . PHP_EOL;

// Essa linha armazena os dados que o usuário digitou nas caixas de texto
        if (file_put_contents($dadosCandidatos, $linha, FILE_APPEND | LOCK_EX)) {
            $mensagem = "Dados salvos com sucesso! (ID: $id)";
        } else {
            $mensagem = "Erro ao gravar o arquivo.";
        }

    } else {
        $mensagem = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="Estilizacao/style.css">
</head>
<body>

<form method="post">
    <h2>Cadastre o candidato </h2>

    <input type="text" name="nome" class="campo" placeholder="Digite o nome do candidato"><br>

     <!-- SELECT TURMA -->
    <select name="turma" id="turma" class="campo" >
        <option value="">Selecione a turma</option>
        <option value="Manhã">Manhã</option>
        <option value="Tarde">Tarde</option>
        <option value="Noite">Noite</option>
    </select>
    <br>

    <!-- SELECT DO CURSO -->
     <select name="curso" id="curso" class="campo" >
        <option value="">Selecione o curso</option>
        <option value="backEnd">Back End</option>
        <option value="designerGrafico">Designer Gráfico</option>
        <option value="frontEnd">Front End</option>
        <option value="Info">T. em Informática</option>
        <option value="jogosDigitais">Jogos Digitais</option>
     </select>


    <button type="submit"> <strong>SALVAR </strong></button>
    
    <a href="index.php">
    <button type="button" class="inicio"> <strong>HOME</strong></button>
    </a>


 <?php if (!empty($mensagem)) echo "<p>$mensagem</p>"; ?>
</form>

</body>
</html>
