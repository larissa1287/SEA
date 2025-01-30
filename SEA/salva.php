<?php
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Diretório para salvar as imagens
    $caminho = 'imagens/';

    // Verifica se a imagem foi enviada corretamente
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        // Recebendo a imagem e manipulando o nome
        $extensao = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION); // Pega a extensão original
        $nome_imagem = uniqid("img_", true) . "." . $extensao; // Gera um nome único
        $destino_imagem = $caminho . $nome_imagem;

        // Move a imagem para o diretório de destino
        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $destino_imagem)) {
            die("Erro ao fazer upload da imagem.");
        }
    } else {
        die("Erro: Nenhuma imagem enviada ou erro no envio.");
    }

    // Inclui a conexão com o banco de dados
    include "db_connect.php";

    // Recebendo os dados do formulário
    $nome_usuario = $_POST["nome"];
    $endereco = $_POST["endereco"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT); // Hashing da senha
    $data_nascimento = $_POST["data_nascimento"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $especialidade = $_POST["especialidade"];  // Nova informação
    $crm_crp = $_POST["crm_crp"];  // Nova informação
    $genero = $_POST["genero"];  // Nova informação

    // Prepara a consulta SQL para inserção dos dados com segurança
    $sql = "INSERT INTO usuarios (nome, endereco, senha, data_nascimento, telefone, email, foto, especialidade, crm_crp, genero) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepara a instrução SQL
    if ($stmt = $con->prepare($sql)) {
        // Liga os parâmetros aos valores
        $stmt->bind_param("ssssssssss", $nome_usuario, $endereco, $senha, $data_nascimento, $telefone, $email, $nome_imagem, $especialidade, $crm_crp, $genero);
        
        // Executa a consulta
        if ($stmt->execute()) {
            // Redireciona para a página de login após o cadastro bem-sucedido
            header("Location: login.php");
            exit();
        } else {
            echo "Erro ao cadastrar o usuário: " . $stmt->error;
        }

        // Fecha o statement
        $stmt->close();
    } else {
        echo "Erro na preparação do SQL: " . $con->error;
    }

    // Fecha a conexão com o banco de dados
    $con->close();
} else {
    echo "Método de requisição inválido.";
}
?>
