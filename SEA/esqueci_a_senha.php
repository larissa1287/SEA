<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/esqueci.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>SEA - Esqueci Minha Senha</title>
</head>
<body>

<header></header> <!-- Cabeçalho vazio -->

<div class="container">
    <h2>Recuperar Senha</h2>
    <p>Digite seu email para receber as instruções de recuperação de senha.</p>
    <form id="forgotPasswordForm" method="POST">
        <div class="form-group full-width">
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group full-width">
            <input type="submit" value="Enviar Instruções">
        </div>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configurações de conexão com o banco de dados
    $servername = "localhost"; // Seu host, geralmente localhost
    $username = "root"; // Seu usuário do banco de dados
    $password = ""; // Sua senha do banco de dados
    $dbname = "sea"; // Nome do seu banco de dados

    // Cria a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica se houve erro na conexão
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Função para gerar um token de recuperação de senha aleatório
    function generateToken() {
        return bin2hex(random_bytes(16)); // Gera um token de 32 caracteres
    }

    $email = $_POST['email'];

    // Valida o email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Email inválido.");</script>';
    } else {
        // Verifica se o email existe no banco de dados
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Email encontrado, gera o token de recuperação
                $user = $result->fetch_assoc();
                $token = generateToken();

                // Salva o token no banco de dados com um tempo de expiração (opcional)
                $sql_token = "UPDATE usuarios SET token_recuperacao = ?, token_expiracao = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
                $stmt_token = $conn->prepare($sql_token);
                $stmt_token->bind_param("ss", $token, $email);
                $stmt_token->execute();

                // Enviar email de recuperação
                $link_recuperacao = "https://seu-dominio.com/redefinir_senha.php?token=$token";
                $assunto = "Recuperação de Senha - SEA";
                $mensagem = "
                    <h2>Recuperação de Senha</h2>
                    <p>Clique no link abaixo para redefinir sua senha:</p>
                    <a href='$link_recuperacao'>$link_recuperacao</a>
                    <p>Este link é válido por 1 hora.</p>";

                // Cabeçalhos do email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: no-reply@sea-apoio.com" . "\r\n";

                // Envia o email
                if (mail($email, $assunto, $mensagem, $headers)) {
                    echo '<script>alert("Instruções de recuperação enviadas para seu email.");</script>';
                } else {
                    echo '<script>alert("Erro ao enviar o email.");</script>';
                }
            } else {
                // Email não encontrado
                echo '<script>alert("Email não cadastrado.");</script>';
            }

            $stmt->close();
        } else {
            echo '<script>alert("Erro na consulta ao banco de dados.");</script>';
        }

        $conn->close();
    }
}
?>

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

</body>
</html>
