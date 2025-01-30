<?php
// Conexão com o banco de dados
$host = 'localhost'; // Endereço do servidor
$dbname = 'sea'; // Nome do banco de dados
$username = 'root'; // Nome de usuário do banco
$password = ''; // Senha do banco

try {
    // Conectar ao banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $disponibilidade = $_POST['disponibilidade'];
        $especializacao = $_POST['especializacao'];
        $formacao = $_POST['formacao'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];

        // Query SQL para inserir os dados na tabela 'usuarios'
        $sql = "INSERT INTO usuarios (nome, disponibilidade, especializacao, formacao, email, telefone) 
                VALUES (:nome, :disponibilidade, :especializacao, :formacao, :email, :telefone)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':disponibilidade', $disponibilidade);
        $stmt->bindParam(':especializacao', $especializacao);
        $stmt->bindParam(':formacao', $formacao);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);

        if ($stmt->execute()) {
            echo "Informações adicionadas com sucesso!";
        } else {
            echo "Erro ao adicionar as informações.";
        }
    }
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>
