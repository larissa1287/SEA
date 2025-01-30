<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastro_profissional.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>SEA - Cadastro de Profissional</title>
    <style>
        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .toggle-password img {
            width: 20px;
            height: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .footer-content {
            text-align: center;
            padding: 20px;
            background: #1b5a72;
            color: white;
        }

        .footer-bottom {
            background: #154a5b;
            text-align: center;
            color: white;
            padding: 10px;
        }
    </style>
    <script>
        function togglePasswordVisibility(id) {
            var senha = document.getElementById(id);
            if (senha.type === "password") {
                senha.type = "text";
            } else {
                senha.type = "password";
            }
        }
    </script>
</head>
<body>

<main class="container">
    <h1>Cadastro de Profissional</h1>
    <p>Já tem cadastro? <a href="login.php">Login</a></p>
    <form id="cadastroFormProfissional" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Nome" required>
        </div>
        <div class="form-group">
            <label for="endereco">Endereço</label>
            <input type="text" id="endereco" name="endereco" placeholder="Endereço" required>
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
        <div class="form-group">
            <label for="especialidade">Especialidade</label>
            <input type="text" id="especialidade" name="especialidade" placeholder="Especialidade" required>
        </div>
        <div class="form-group">
            <label for="crm_crp">CRM/CRP</label>
            <input type="text" id="crm_crp" name="crm_crp" placeholder="CRM/CRP" required>
        </div>
        <div class="form-group">
            <label for="genero">Gênero</label>
            <select id="genero" name="genero" required>
                <option value="">Selecione o gênero</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
                <option value="outro">Outro</option>
                <option value="prefiro_nao_dizer">Prefiro não dizer</option>
            </select>
        </div>
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Cadastrar">
        </div>
    </form>
</main>

<footer>
        <div class="footer-content">
            <div class="logo">
                <img src="./Imagens/logo_branca.png" alt="Logo SEA">
            </div>
            <div class="contact-info">
                <p>Suporte Emocional e Acolhimento</p>
                <p><a href="mailto:contato@sea-apoio.com">contato@sea-apoio.com</a></p>
                <p>(12) 91234-5678</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
        </div>
    </footer>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sea";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $especialidade = $_POST['especialidade'];
    $crm_crp = $_POST['crm_crp'];
    $genero = $_POST['genero'];

    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
    }

    $nivel = 1;

    $sql = "INSERT INTO usuarios (nome, endereco, email, telefone, data_nascimento, senha, especialidade, crm_crp, genero, foto, nivel) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssssssi", $nome, $endereco, $email, $telefone, $data_nascimento, $senha, $especialidade, $crm_crp, $genero, $foto, $nivel);

        if ($stmt->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
</body>
</html>
