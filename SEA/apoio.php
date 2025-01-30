<?php
session_start();

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

    $usuario_id = $_SESSION['usuario_id'];

    // Consulta o nível do usuário
    $sql = "SELECT nivel FROM usuarios WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $usuario_id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $nivel_usuario = $usuario['nivel'];

    // Cidades do Vale do Paraíba Paulista
    $sql_cidades = "SELECT DISTINCT cidade FROM informacoes_psicologos 
                    WHERE cidade IN ('Aparecida', 'Arapeí', 'Areias', 'Bananal', 'Cachoeira Paulista', 
                                     'Caçapava', 'Campos do Jordão', 'Canas', 'Caraguatatuba', 'Cruzeiro', 
                                     'Cunha', 'Guaratinguetá', 'Igaratá', 'Ilhabela', 'Jacareí', 
                                     'Jambeiro', 'Lagoinha', 'Lavrinhas', 'Lorena', 'Monteiro Lobato', 
                                     'Natividade da Serra', 'Paraibuna', 'Pindamonhangaba', 'Potim', 
                                     'Redenção da Serra', 'Roseira', 'Santa Branca', 
                                     'Santo Antônio do Pinhal', 'São Bento do Sapucaí', 
                                     'São José dos Campos', 'São Luiz do Paraitinga', 'Taubaté', 
                                     'Tremembé', 'Ubatuba')
                    ORDER BY cidade ASC";
    $stmt_cidades = $conn->prepare($sql_cidades);
    $stmt_cidades->execute();
    $cidades = $stmt_cidades->fetchAll(PDO::FETCH_ASSOC);

    // Filtragem por cidade
    $cidade_selecionada = isset($_GET['cidade']) ? $_GET['cidade'] : '';

    $sql_info = "SELECT id, nome, disponibilidade, especializacao, formacao, email, telefone, foto FROM informacoes_psicologos";
    if (!empty($cidade_selecionada)) {
        $sql_info .= " WHERE cidade = :cidade";
        $stmt_info = $conn->prepare($sql_info);
        $stmt_info->bindParam(':cidade', $cidade_selecionada);
    } else {
        $stmt_info = $conn->prepare($sql_info);
    }

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
    <link rel="stylesheet" href="css/apoio.css">
    <link rel="stylesheet" href="css/responsivo.css">

    <script>
        function confirmarExclusao(id) {
            if (confirm("Tem certeza de que deseja excluir este registro?")) {
                window.location.href = "excluirInformacao.php?id=" + id;
            }
        }
    </script>
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

<div class="container">
    <h1>Psicólogos Disponíveis</h1>

    <div class="cidade-seletor">
        <form method="GET" action="apoio.php">
            <select name="cidade" id="cidade-seletor" onchange="this.form.submit()">
                <option value="">Todas as Cidades</option>
                <?php foreach ($cidades as $cidade): ?>
                    <option value="<?= htmlspecialchars($cidade['cidade']) ?>"
                        <?= $cidade_selecionada === $cidade['cidade'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cidade['cidade']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php
    if (isset($_GET['mensagem']) && $_GET['mensagem'] == 'excluido') {
        echo '<p style="color: green; text-align: center;">Registro excluído com sucesso!</p>';
    }
    ?>

    <?php if ($nivel_usuario == 1): ?>
        <button id="create-info-btn" onclick="window.location.href='criarInformacao.php'">Criar Quadro de Informações</button>
    <?php endif; ?>

    <div class="profissionais-container">
        <?php
        if (count($informacoes) > 0) {
            foreach ($informacoes as $info) {
                echo '<div class="profissional-bloco">';

                if (!empty($info['foto'])) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($info['foto']) . '" alt="Foto do profissional" class="foto-profissional">';
                }

                echo '<div class="profissional-info">';
                echo '<h2>' . htmlspecialchars($info['nome']) . '</h2>';
                echo '<p><strong>Disponibilidade:</strong> ' . htmlspecialchars($info['disponibilidade']) . '</p>';
                echo '<p><strong>Especialização:</strong> ' . htmlspecialchars($info['especializacao']) . '</p>';
                echo '<p><strong>Formação:</strong> ' . htmlspecialchars($info['formacao']) . '</p>';
                echo '<p><strong>Email:</strong> ' . htmlspecialchars($info['email']) . '</p>';
                echo '<p><strong>Telefone:</strong> ' . htmlspecialchars($info['telefone']) . '</p>';
                echo '</div>';

                if ($nivel_usuario == 1) {
                    echo '<a href="#" onclick="confirmarExclusao(' . $info['id'] . ')" class="delete-btn">Excluir</a>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>Nenhum psicólogo disponível no momento.</p>';
        }
        ?>
    </div>
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
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 SEA. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
