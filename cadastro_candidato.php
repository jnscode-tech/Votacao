<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Recebe os dados do formulário e remove espaços extras
//O trim() remove espaços em branco desnecessários do início e do fim de uma string.
    $nome  = trim($_POST["nome"]);
    $turma = trim($_POST["turma"]);
    $curso = trim($_POST["curso"]);

// O if verifica se os campos: nome, turma e curso estão vazios, caso esteja ele dá mensagem e não permite salvar dados

    if (!empty($nome) && !empty($turma) && !empty($curso)) {

// variavel dadosCandidatos, armazena as informações no dados.txt

//Foi necessário dar permissão de ecrita e leitura na pasta para que o arquivo txt fosse criado  
//   Caminho do arquivo de candidatos
        $dadosCandidatos = __DIR__ . "/dados/dados.txt";

// Se o arquivo existir, lê as linhas; senão, cria um array vazio
// GERA ID SEQUENCIAL  - 1,2, 3....
        if (file_exists($dadosCandidatos)) {
            $linhas = file($dadosCandidatos, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Gera ID sequencial baseado na quantidade de linhas
            $id = count($linhas) + 1;
        } else {
            $id = 1;
        }
//a forma como os dados serão armazenados no bloco de notas: ID: 1 | Nome: Juliana | Turma: Noite | Curso: ADS
        $linha = "$id| $nome|$turma|$curso" . PHP_EOL;

// Essa linha armazena os dados que o usuário digitou nas caixas de texto no bloco de notas
        if (file_put_contents($dadosCandidatos, $linha, FILE_APPEND | LOCK_EX)) {

 //Mensagem para o usuario - na cor azul (span style=color blue)       
            $mensagem = "<span style='color: blue;'>Dados salvos com sucesso! </span> (ID: $id)";
        } else {
 //Mensagem para o usuario - na cor vermelha (span style=color blue)   
            $mensagem = "<span style='color: red;'> Erro ao gravar o arquivo. </span>";
        }
    } else {
 //Mensagem para o usuario - na cor verde (span style=color blue)   
        $mensagem = "<span style='color: green;'>Preencha todos os campos.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="Estilizacao/style2.css">
      <!-- comando abaixo serve para colocar ícone no título -->
    <link rel="icon" type="image/png" href="Images/icon2.png">
 

</head>
<body>

<form method="post">
    <h2>Cadastre o candidato </h2>

    <input type="text" name="nome" placeholder="Digite o nome do candidato"><br>

     <!-- SELECT TURMA -->
    <select name="turma" id="turma">
        <option value="">Selecione a turma</option>
        <option value="Manhã">Manhã</option>
        <option value="Tarde">Tarde</option>
        <option value="Noite">Noite</option>
    </select>
    <br>

    <!-- SELECT DO CURSO -->
     <select name="curso" id="curso">
        <option value="">Selecione o curso</option>
        <option value="backEnd">Back End</option>
        <option value="designerGrafico">Designer Gráfico</option>
        <option value="frontEnd">Front End</option>
        <option value="Info">T. em Informática</option>
        <option value="jogosDigitais">Jogos Digitais</option>
     </select>

      <!--  ÁREA DOS BOTÕES – Para Salvar tem que utilizar o submit, 
      já para voltar pra HOME usa o button pois ele não terá uma ação (get/post) e tá vinculado ao 
      link que retorna para página incial -->

    <button type="submit"> <strong>SALVAR </strong></button>
    
    <a href="index.php">
    <button type="button" class="inicio"> <strong>HOME</strong></button>
    </a>


 <?php if (!empty($mensagem)) echo "<p>$mensagem</p>"; ?>
</form>

</body>
</html>
