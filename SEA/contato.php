<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.php");
    exit(); // Termina o script após o redirecionamento
}

// Busca o nome do usuário na sessão
$nome = $_SESSION['usuario_nome'] ?? "Visitante"; // Usa o nome armazenado na sessão
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Faz com que a página seja responsiva -->
    <title>SEA - Bem-vindo(a)</title> <!-- Título da página que aparece na aba do navegador -->
    <link rel="stylesheet" href="css/contato.css"> <!-- Importa o arquivo de estilos CSS externo -->
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
    <main>
        <!-- Conteúdo adicional dos serviços -->
        <section class="services-section">
            <h2>Nossos Serviços</h2>
            <ul>
                <li><strong>Aconselhamento e Terapia:</strong> Sessões de terapia individual e em grupo com profissionais qualificados.</li>
                <li><strong>Recursos Educacionais:</strong> Artigos, vídeos e cursos online sobre diversas condições psicossociais.</li>
                <li><strong>Comunidade de Apoio:</strong> Fóruns e grupos de apoio para troca de experiências e solidariedade.</li>
                <li><strong>Programas de Bem-Estar:</strong> Atividades e workshops focados na saúde mental.</li>
            </ul>
        </section>

        <!-- Conteúdo adicional: Junte-se a Nós -->
        <section class="join-us-section">
            <h2>Junte-se a Nós</h2>
            <p>Descubra como podemos ajudar você ou alguém que você ama. Explore nossos recursos, participe de nossos programas e faça parte de uma comunidade dedicada ao bem-estar psicossocial.</p>
        </section>
    </main>

    <script src="js/scripts.js"></script>

    <!-- Incluímos o footer -->
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

    <script src="js/scripts.js"></script>
</body>
</html>
