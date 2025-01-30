<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado corretamente
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nivel'])) {
    die("Erro: usuário não está logado.");
}

// Define as variáveis a partir da sessão
$user_id = $_SESSION['usuario_id'];
$nivel = $_SESSION['usuario_nivel']; // 1 = Admin, 2 = Convidado

// Configurações de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sea";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$perfilAtualizado = false; // Variável para controlar o status de atualização

// Verifica se o formulário foi enviado para atualizar as informações
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_mode'])) {
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';

    // Verifica se há um arquivo de foto (permitir para todos os usuários)
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_type = mime_content_type($_FILES['foto']['tmp_name']);
        if (in_array($file_type, ['image/jpeg', 'image/png'])) {
            $foto = file_get_contents($_FILES['foto']['tmp_name']);
        } else {
            die("Erro: O arquivo enviado não é uma imagem válida.");
        }
    }

    // Prepara a consulta de atualização
    $sql = "UPDATE usuarios SET endereco=?, email=?, telefone=?";

    // Se houver nova foto, atualiza também
    if (!empty($foto)) {
        $sql .= ", foto=?";
    }

    $sql .= " WHERE id=?";

    // Prepara a declaração SQL
    if ($stmt = $conn->prepare($sql)) {
        if (!empty($foto)) {
            $stmt->bind_param("ssssi", $endereco, $email, $telefone, $foto, $user_id);
        } else {
            $stmt->bind_param("sssi", $endereco, $email, $telefone, $user_id);
        }

        if ($stmt->execute()) {
            $perfilAtualizado = true; // Marca que o perfil foi atualizado com sucesso
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro ao preparar a consulta: " . $conn->error;
    }
}

// Busca as informações do usuário
$sql = "SELECT nome, endereco, email, telefone, data_nascimento, foto FROM usuarios WHERE id=?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nome, $endereco, $email, $telefone, $data_nascimento, $foto);
    $stmt->fetch();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <style>
        input[disabled] {
            background-color: #f0f0f0;
            color: #777;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function toggleEditMode() {
            var inputs = document.querySelectorAll("input");
            var editMode = document.getElementById("edit_mode").value;

            if (editMode === "0") {
                document.getElementById("edit_mode").value = "1";
                inputs.forEach(function(input) {
                    // Apenas o campo de nome e data de nascimento ficam desabilitados
                    if (input.id !== "nome" && input.id !== "data_nascimento") {
                        input.disabled = false;
                    }
                });
                document.getElementById("edit_button").style.display = "none";
                document.getElementById("save_button").style.display = "inline";
            } else {
                document.getElementById("edit_mode").value = "0";
                inputs.forEach(function(input) {
                    if (input.id !== "nome" && input.id !== "data_nascimento") {
                        input.disabled = true;
                    }
                });
                document.getElementById("edit_button").style.display = "inline";
                document.getElementById("save_button").style.display = "none";
            }
        }

        // Função para mostrar o pop-up de sucesso
        function showSuccessPopup() {
            alert("Perfil atualizado com sucesso!");
        }

        // Verifica se o perfil foi atualizado e chama a função para o pop-up
        <?php if ($perfilAtualizado): ?>
            window.onload = function() {
                showSuccessPopup();
            }
        <?php endif; ?>
    </script>
</head>
<body>

<a href="inicio.php" class="back-button">Voltar para Início</a>

<div class="container">
    <h2>Perfil</h2>

    <div class="profile-pic">
        <?php if ($foto): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($foto); ?>" alt="Foto de perfil">
        <?php else: ?>
            <img src="default-avatar.png" alt="Foto de perfil padrão">
        <?php endif; ?>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" id="edit_mode" name="edit_mode" value="0">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" disabled>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($endereco); ?>" disabled required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled required>

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" disabled required>

        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>" disabled>

        <!-- Permitir upload de foto para todos os usuários -->
        <label for="foto">Alterar Foto:</label>
        <input type="file" id="foto" name="foto" disabled>

        <button type="button" id="edit_button" onclick="toggleEditMode()">Editar</button>
        <button type="submit" id="save_button" style="display: none;">Salvar</button>
    </form>
</div>

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
            <div class="social-media">
                <a href="#"><img src="./Imagens/insta-logo.png" alt="Instagram"></a>
                <a href="#"><img src="./Imagens/face-logo.png" alt="Facebook"></a>
                <a href="#"><img src="./Imagens/x-logo.png" alt="Twitter"></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>

</body>
</html>
