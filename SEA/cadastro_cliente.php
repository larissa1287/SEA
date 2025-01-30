<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastro_cliente.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>SEA - Cadastro de Cliente</title>
</head>
<body>

<main class="container">
    <h1>Cadastro de Cliente</h1>
    <p>Já tem cadastro? <a href="login.php">Login</a></p>
    <form id="cadastroFormCliente" action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Nome" required>
        </div>
        <div class="form-group">
            <label for="endereco">Endereço</label>
            <input type="text" id="endereco" name="endereco" placeholder="Endereço" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Senha" required>
            <span class="toggle-password" onclick="togglePasswordVisibility('senha')">
                <img src="./Imagens/fechado.png" alt="Mostrar senha">
            </span>
        </div>
        <div class="form-group">
            <label for="confirmar_senha">Confirmar Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar Senha" required>
            <span class="toggle-password" onclick="togglePasswordVisibility('confirmar_senha')">
                <img src="./Imagens/fechado.png" alt="Mostrar senha">
            </span>
        </div>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" id="telefone" name="telefone" placeholder="Telefone" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="confirmar_email">Confirmar Email</label>
            <input type="email" id="confirmar_email" name="confirmar_email" placeholder="Confirmar Email" required>
        </div>
        <div class="form-group full-width">
            <input type="submit" value="Cadastrar">
        </div>
    </form>
</main>

<footer>
    <div class="logo">
        <img src="./Imagens/logo_branca.png" alt="Logo SEA">
    </div>
    <div class="contact-info">
        <p>Suporte Emocional e Acolhimento</p>
        <p><a href="mailto:contato@sea-apoio.com">contato@sea-apoio.com</a></p>
        <p>(12) 91234-5678</p>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
    </div>
</footer>

<script>
    function togglePasswordVisibility(id) {
        var senha = document.getElementById(id);
        senha.type = senha.type === "password" ? "text" : "password";
    }
</script>

<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sea";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha = isset($_POST['senha']) ? password_hash($_POST['senha'], PASSWORD_DEFAULT) : '';

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    $nivel = 2;

    if (!empty($nome) && !empty($endereco) && !empty($email) && !empty($telefone) && !empty($data_nascimento) && !empty($senha)) {
        $sql = "INSERT INTO usuarios (nome, endereco, email, telefone, data_nascimento, senha, foto, nivel) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssi", $nome, $endereco, $email, $telefone, $data_nascimento, $senha, $foto, $nivel);

            if ($stmt->execute()) {
                echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php';</script>";
            } else {
                echo "Erro ao cadastrar: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    } else {
        echo "<script>alert('Preencha todos os campos!');</script>";
    }
}

$conn->close();
?>
</body>
</html>
