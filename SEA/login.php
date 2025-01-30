<?php
session_start(); // Inicia a sessão

// Configurações de conexão com o banco de dados
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

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Previne injeção de SQL e sanitiza entradas
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    // Verifica se o email foi preenchido corretamente
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } else {
        // Busca o usuário pelo email
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Armazena as informações do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_nivel'] = $usuario['nivel']; // Nível do usuário (1 = Admin, 2 = Comum)
            $_SESSION['username'] = $usuario['nome']; // Definindo 'username' para compatibilidade com apoio.php

            // Redireciona o usuário de acordo com o nível
            if ($_SESSION['usuario_nivel'] == 1) {
                header("Location: inicio.php"); // Redireciona para o perfil do admin
            } else {
                header("Location: inicio.php"); // Redireciona para o perfil comum
            }
            exit();
        } else {
            $erro = "Email ou senha incorretos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>SEA - Login</title>
</head>
<body>

<header>
    <!-- Cabeçalho vazio -->
</header>

<main class="container">
    <h2>Login</h2>
    <p>Não tem uma conta? <a href="index.html">Cadastre-se</a></p>

    <?php if (isset($erro)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form id="loginForm" method="POST" aria-label="Formulário de Login">
        <div class="form-group full-width">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required aria-required="true">
        </div>
        <div class="form-group full-width">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Senha" required aria-required="true">
        </div>
        <div class="form-group full-width">
            <input type="submit" value="Login">
        </div>
    </form>
    <a href="esqueci_a_senha.php" class="esqueci-senha">Esqueci minha senha</a>
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

</body>
</html>
