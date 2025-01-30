<?php
// Verifica se a sessão já está ativa antes de chamar session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Configuração do banco de dados
$host = "localhost";
$user = "root"; // Ajuste conforme necessário
$pass = "";     // Ajuste conforme necessário
$dbname = "sea";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Insere conselho no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conselho'])) {
    $conselho = trim($_POST['conselho']);
    $user_id = $_SESSION['usuario_id'];

    if (!empty($conselho)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO conselhos (user_id, conselho) VALUES (?, ?)");
            $stmt->execute([$user_id, $conselho]);
        } catch (PDOException $e) {
            die("Erro ao inserir o conselho: " . $e->getMessage());
        }
    }
}

// Busca o nome do usuário na sessão
$nome = $_SESSION['usuario_nome'] ?? "Visitante";

// Busca conselhos do banco de dados
$stmt = $pdo->prepare("SELECT c.conselho, u.nome FROM conselhos c JOIN usuarios u ON c.user_id = u.id ORDER BY c.id DESC");
$stmt->execute();
$conselhos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conselhos - SEA</title>
    <link rel="stylesheet" href="css/conselho.css">
    <link rel="stylesheet" href="css/responsivo.css">
</head>
<body>
<header>
    <div class="logo">
        <a href="index.html">
            <img src="Imagens/logo_branca.png" alt="Logo SEA">
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
</header>

<main>
    <section class="conselho-form">
        <h2>Enviar Conselho</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <textarea name="conselho" placeholder="Escreva seu conselho aqui..." required></textarea><br>
            <button type="submit">Enviar</button>
        </form>
    </section>
</main>

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
