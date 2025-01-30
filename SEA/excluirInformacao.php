<?php
session_start();

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] != 1) {
    header("Location: login.php");
    exit();
}

// Conecta ao banco de dados
$host = 'localhost';
$dbname = 'sea';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        // Exclui o registro
        $sql = "DELETE FROM informacoes_psicologos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            header("Location: apoio.php?mensagem=excluido");
            exit();
        } else {
            echo "Erro ao excluir o registro.";
        }
    } else {
        echo "ID inválido.";
    }
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>
