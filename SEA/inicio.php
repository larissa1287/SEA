<?php
// Verifica se a sessão já está ativa antes de chamar session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.php");
    exit(); // Termina o script após o redirecionamento
}

// Conexão ao banco de dados
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sea";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Busca o nome do usuário na sessão
$nome = $_SESSION['usuario_nome'] ?? "Visitante"; // Usa o nome armazenado na sessão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEA - Bem-vindo(a)</title>
    <link rel="stylesheet" href="css/inicio.css">
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
                    echo '<a href="feedback.php">Feedback</a>';
                } else { // Usuário comum
                    echo '<a href="perfil.php">Meu Perfil</a>';
                    echo '<a href="feedback.php">Feedback</a>'; // Link para feedback
                }
                echo '<a href="logout.php">Sair</a>'; // Link para logoff
            } else {
                echo '<a href="login.php">Login</a>'; // Redireciona para login se não estiver logado
            }
            ?>
        </div>
    </div>
</header>

<section class="welcome-section">
    <div class="welcome-message">
        <h2>Seja Bem-vindo(a), <?php echo htmlspecialchars($nome); ?>!</h2>
        <p>Estamos felizes em tê-lo(a) aqui! Agora você pode explorar os recursos disponíveis, conectar-se com profissionais de saúde mental em sua região e começar sua jornada em direção ao bem-estar.</p>
    </div>
    <div class="highlight-image">
        <img src="Imagens/mar.png" alt="Imagem de Destaque">
    </div>
</section>

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
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>

</body>
</html>
