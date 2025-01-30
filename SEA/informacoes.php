<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
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

    // Busca o ID do usuário armazenado na sessão
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta o banco para obter o nível do usuário
    $sql = "SELECT nivel FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $usuario_id);
    $stmt->execute();

    // Recupera o nível do usuário
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $nivel_usuario = $usuario['nivel']; // Nível 1 = Admin, Nível 2 = Cliente

    // Busca as informações dos psicólogos
    $sql_info = "SELECT * FROM informacoes_psicologos";
    $stmt_info = $conn->prepare($sql_info);
    $stmt_info->execute();
    $informacoes = $stmt_info->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEA - Psicólogos Disponíveis</title>
    <link rel="stylesheet" href="css/info.css">
    <link rel="stylesheet" href="css/responsivo.css">
</head>
<body>
<header>
    <div class="logo">
        <a href="index.html">
            <img src="Imagens/logo.png" alt="Logo SEA">
        </a>
    </div>
    <nav>
        <ul>
            <li><a href="inicio.php">Início</a></li>
            <li><a href="redirecionar.php">Exercícios</a></li>
            <li><a href="contato.php">Contato</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="apoio.php">Apoio</a></li>
        </ul>
    </nav>
    <div class="usuario">
        <img src="Imagens/usuario.png" alt="Ícone do Usuário" id="usuario-icon">
        <div id="login-popup">
            <?php
            // Verifica se o usuário está logado e redireciona com base no nível do usuário
            if (isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_nivel'])) {
                // Exibe o link para o perfil de acordo com o nível do usuário
                if ($_SESSION['usuario_nivel'] == 1) { // Admin
                    echo '<a href="perfil_adm.php">Meu Perfil</a>';
                } else { // Usuário comum
                    echo '<a href="perfil.php">Meu Perfil</a>';
                }
                echo '<a href="logout.php">Sair</a>'; // Link para logoff
            } else {
                echo '<a href="login.php">Login</a>'; // Redireciona para login se não estiver logado
            }
            ?>
        </div>
    </div>
</header>

<div class="container">
    <h1>Psicólogos Disponíveis</h1>

    <?php if ($nivel_usuario == 1): ?>
        <!-- Exibe o botão de criação apenas para administradores -->
        <button id="create-info-btn" onclick="window.location.href='criarInformacao.php'">Criar Quadro de Informações</button>
    <?php endif; ?>

    <div class="profissionais-container">
        <?php
        // Verifica se há informações disponíveis
        if (count($informacoes) > 0) {
            foreach ($informacoes as $info) {
                echo '<div class="profissional-bloco">';
                echo '<img src="/mnt/data/image.png" alt="Foto do profissional">'; // Usando a imagem enviada
                echo '<h2>' . htmlspecialchars($info['nome']) . '</h2>';
                echo '<p><strong>Disponibilidade:</strong> ' . htmlspecialchars($info['disponibilidade']) . '</p>';
                echo '<p><strong>Especialização:</strong> ' . htmlspecialchars($info['especializacao']) . '</p>';
                echo '<p><strong>Formação:</strong> ' . htmlspecialchars($info['formacao']) . '</p>';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($info['email']) . '</p>';
                echo '<p><strong>Telefone:</strong> ' . htmlspecialchars($info['telefone']) . '</p>';
                echo '</div>';
            }
        } else {
            // Exibe a mensagem se não houver psicólogos disponíveis
            if ($nivel_usuario == 2) {
                echo '<p>Nenhum psicólogo disponível no momento.</p>';
            }
        }
        ?>
    </div>
</div>

<script src="js/scripts.js"></script>

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
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>
