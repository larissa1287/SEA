<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Busca o nome e nível do usuário na sessão
$nome = $_SESSION['usuario_nome'] ?? "Visitante";
$nivel = $_SESSION['usuario_nivel'] ?? 0;

// Configuração do banco de dados
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
    <title>SEA - Conselhos</title>
    <link rel="stylesheet" href="css/conselhos.css">
    <link rel="stylesheet" href="css/responsivo.css">
</head>
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


<body>
    <main class="container">
        <section class="advice-section">
            <h2>CONSELHOS</h2>
            
            <!-- Botão para adicionar conselhos -->
            <?php if ($nivel == 1): ?>
                <div class="add-advice-button">
                    <a href="criarConselho.php" class="btn">Adicionar Conselhos</a>
                </div>
            <?php endif; ?>

            <!-- Conselhos fixos -->
            <div class="advice">
                <h3>Priorize seu autocuidado:</h3>
                <p>Dedique alguns minutos do seu dia para fazer algo que você gosta, como ler, ouvir música ou simplesmente relaxar.</p>
            </div>
            <div class="advice">
                <h3>Desenvolva uma Rotina:</h3>
                <p>Ter uma rotina diária pode trazer uma sensação de controle e estabilidade, o que é especialmente importante em momentos de incerteza.</p>
            </div>
            <div class="advice">
                <h3>Reconheça seus Limites:</h3>
                <p>É importante aceitar que nem todos os dias serão perfeitos e que está tudo bem em ter momentos difíceis.</p>
            </div>

            <!-- Conselhos dinâmicos -->
            <?php if (count($conselhos) > 0): ?>
                <?php foreach ($conselhos as $conselho): ?>
                    <div class="advice dynamic">
                        <h3>Conselho de <?php echo htmlspecialchars($conselho['nome']); ?>:</h3>
                        <p><?php echo htmlspecialchars($conselho['conselho']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum conselho disponível no momento.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
    <div class="footer-content">
        <div class="contact-info">
            <p>Suporte Emocional e Acolhimento</p>
            <p><a href="mailto:contato@sea-apio.com">contato@sea-apio.com</a></p>
            <p>(12) 91234-5678</p>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="rights">
            <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
        </div>
        <div class="social">
            <!-- Adicione aqui os ícones de mídias sociais, se houver -->
        </div>
    </div>
</footer>



    <script src="scripts.js"></script>
</body>
</html>
