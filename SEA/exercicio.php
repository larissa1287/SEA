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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura o viewport para tornar o site responsivo -->
    <title>Cronômetro de Respiração 5-8-9</title> <!-- Título da página -->
    <link rel="stylesheet" href="css/exercicio.css"> <!-- Conecta o arquivo CSS externo -->
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

<main>
    <div class="timer-container">
        <div id="display">00:00</div> <!-- Exibição do tempo restante -->
    </div>
    <div id="status"></div> <!-- Exibição do status do exercício -->
    <button id="controlButton">INICIAR</button> <!-- Botão de controle -->
</main>

<script src="js/exercicio.js"></script> <!-- Conecta o arquivo JavaScript externo -->

<script>
    let intervals = [4, 7, 8]; 
    let currentInterval = 0; 
    let timer; 
    let isRunning = false; 
    const display = document.getElementById('display'); 
    const status = document.getElementById('status'); 
    const controlButton = document.getElementById('controlButton'); 

    controlButton.addEventListener('click', () => {
        if (!isRunning) {
            startBreathing(); 
        } else {
            stopBreathing(); 
        }
    });

    function startBreathing() {
        isRunning = true; 
        controlButton.textContent = 'PARAR'; 
        currentInterval = 0; 
        runInterval(); 
    }

    function runInterval() {
        let time = intervals[currentInterval]; 
        let countdown = time; 
        updateStatus(); 
        display.textContent = formatTime(countdown); 

        timer = setInterval(() => {
            countdown--; 
            display.textContent = formatTime(countdown); 

            if (countdown < 0) { 
                clearInterval(timer); 
                currentInterval++; 
                if (currentInterval < intervals.length) { 
                    runInterval(); 
                } else {
                    stopBreathing(); 
                }
            }
        }, 1000); 
    }

    function stopBreathing() {
        isRunning = false; 
        clearInterval(timer); 
        controlButton.textContent = 'INICIAR'; 
        display.textContent = '00:00'; 
        status.textContent = ''; 
    }

    function updateStatus() {
        switch(currentInterval) {
            case 0:
                status.textContent = 'Inspire...'; 
                break;
            case 1:
                status.textContent = 'Segure a respiração...'; 
                break;
            case 2:
                status.textContent = 'Expire...'; 
                break;
        }
    }

    function formatTime(seconds) {
        return `00:${seconds < 10 ? '0' : ''}${seconds}`; 
    }

    const usuarioIcon = document.getElementById('usuario-icon'); 
    const loginPopup = document.getElementById('login-popup'); 

    usuarioIcon.addEventListener('click', () => {
        loginPopup.style.display = loginPopup.style.display === 'block' ? 'none' : 'block';
    });

    window.addEventListener('click', (event) => {
        if (!usuarioIcon.contains(event.target) && !loginPopup.contains(event.target)) {
            loginPopup.style.display = 'none';
        }
    });
</script>

<script src="js/scripts.js"></script>
<footer>
    <div class="footer-content">
        <div class="logo-and-line">
            <div class="logo">
                <img src="./Imagens/logo_branca.png" alt="Logo SEA">
            </div>
            <div class="vertical-line"></div>
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
