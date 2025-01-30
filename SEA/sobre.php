<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
$logado = isset($_SESSION['usuario_id']); // Verifica se o ID do usuário está na sessão
$nome_usuario = $logado ? $_SESSION['usuario_nome'] : "Visitante"; // Pega o nome do usuário logado ou define como "Visitante"
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEA</title>
    <link rel="stylesheet" href="css/sobre.css">
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

    <!-- Início do conteúdo principal com os textos solicitados -->
    <section>
        <div class="section-content">
            <h2>Sobre nós</h2>
            <p>Seja bem-vindo ao SEA, onde o Suporte Emocional e Acolhimento são mais do que princípios – são nossa missão e nossa paixão.
               Ao integrar suporte emocional e acolhimento, criamos um ambiente holístico e inclusivo que promove o bem-estar e a autonomia de cada indivíduo. 
               Seja através de sessões de terapia, grupos de apoio ou recursos educativos, nosso compromisso é oferecer o suporte necessário para que cada pessoa possa prosperar.
            </p>
        </div>
        <div class="section-content">
            <h2>Nossos Valores</h2>
            <ul>
                <li><strong>Empatia:</strong> Oferecemos apoio compassivo e sem julgamento.</li>
                <li><strong>Inclusão:</strong> Criamos um espaço onde todos se sentem bem-vindos e valorizados.</li>
                <li><strong>Autonomia:</strong> Incentivamos o desenvolvimento de habilidades para a autogestão da saúde mental.</li>
                <li><strong>Confidencialidade:</strong> Priorizamos a privacidade e a confidencialidade de nossos membros.</li>
            </ul>
        </div>
        <div class="section-content">
            <h2>Nossa Missão</h2>
            <p>Proporcionar suporte holístico que abrange o bem-estar mental, emocional e social, promovendo a inclusão e a independência de cada pessoa.</p>
        </div>
    </section>
    <!-- Fim do conteúdo principal -->

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

    <script src="scripts.js"></script>
</body>
</html>
