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

    // Verifica se o usuário é administrador
    if ($nivel_usuario != 1) {
        header("Location: apoio.php");
        exit();
    }

    // Função para redimensionar e arredondar imagens (se GD estiver habilitada)
    function redimensionarImagem($fotoConteudo) {
        if (!extension_loaded('gd')) {
            return $fotoConteudo; // Retorna a imagem original se GD não estiver disponível
        }

        $imagemOriginal = imagecreatefromstring($fotoConteudo);
        if (!$imagemOriginal) {
            return $fotoConteudo;
        }

        $larguraOriginal = imagesx($imagemOriginal);
        $alturaOriginal = imagesy($imagemOriginal);
        $novaLargura = 200; // Define a nova largura
        $novaAltura = 200; // Define a nova altura

        $imagemRedimensionada = imagecreatetruecolor($novaLargura, $novaAltura);
        imagecopyresampled($imagemRedimensionada, $imagemOriginal, 0, 0, 0, 0, $novaLargura, $novaAltura, $larguraOriginal, $alturaOriginal);

        // Tornar a imagem arredondada
        $imagemFinal = imagecreatetruecolor($novaLargura, $novaAltura);
        imagesavealpha($imagemFinal, true);
        $corTransparente = imagecolorallocatealpha($imagemFinal, 0, 0, 0, 127);
        imagefill($imagemFinal, 0, 0, $corTransparente);

        $raio = $novaLargura / 2;
        for ($y = 0; $y < $novaAltura; $y++) {
            for ($x = 0; $x < $novaLargura; $x++) {
                $distancia = sqrt(pow($x - $raio, 2) + pow($y - $raio, 2));
                if ($distancia <= $raio) {
                    $cor = imagecolorat($imagemRedimensionada, $x, $y);
                    imagesetpixel($imagemFinal, $x, $y, $cor);
                }
            }
        }

        ob_start();
        imagepng($imagemFinal);
        $conteudoFinal = ob_get_clean();

        imagedestroy($imagemOriginal);
        imagedestroy($imagemRedimensionada);
        imagedestroy($imagemFinal);

        return $conteudoFinal;
    }

    // Verifica se o formulário foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $disponibilidade = $_POST['disponibilidade'];
        $especializacao = $_POST['especializacao'];
        $formacao = $_POST['formacao'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cidade = $_POST['cidade']; // Cidade selecionada

        // Lida com a foto (opcional)
        $foto = null;
        if (!empty($_FILES['foto']['tmp_name'])) {
            $fotoConteudo = file_get_contents($_FILES['foto']['tmp_name']);
            $foto = redimensionarImagem($fotoConteudo);
        }

        // Query SQL para inserir os dados na tabela 'informacoes_psicologos'
        $sql = "INSERT INTO informacoes_psicologos (nome, disponibilidade, especializacao, formacao, email, telefone, cidade, foto) 
                VALUES (:nome, :disponibilidade, :especializacao, :formacao, :email, :telefone, :cidade, :foto)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':disponibilidade', $disponibilidade);
        $stmt->bindParam(':especializacao', $especializacao);
        $stmt->bindParam(':formacao', $formacao);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            header("Location: apoio.php");
            exit();
        } else {
            echo "Erro ao adicionar as informações.";
        }
    }
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Informações - SEA</title>
    <link rel="stylesheet" href="css/informacao.css">
    <link rel="stylesheet" href="css/responsivo.css">
</head>
<body>
    <header>
        <div class="back-link">
            <a href="apoio.php">Voltar</a>
        </div>
    </header>

    <div class="form-container">
        <h2>Adicionar Informações do Profissional</h2>
        <form action="criarInformacao.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome do Profissional:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="disponibilidade">Disponibilidade:</label>
            <input type="text" id="disponibilidade" name="disponibilidade" required>

            <label for="especializacao">Especialização:</label>
            <input type="text" id="especializacao" name="especializacao" required>

            <label for="formacao">Formação:</label>
            <input type="text" id="formacao" name="formacao" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>

            <label for="foto">Foto do Profissional (Opcional):</label>
            <input type="file" id="foto" name="foto" accept="image/*">

           <!-- Exibição para selecionar cidades -->
<label for="cidade">Selecione a cidade:</label>
<select id="cidade" name="cidade" required>
    <option value="Aparecida">Aparecida</option>
    <option value="Arapeí">Arapeí</option>
    <option value="Areias">Areias</option>
    <option value="Bananal">Bananal</option>
    <option value="Caçapava">Caçapava</option>
    <option value="Cachoeira Paulista">Cachoeira Paulista</option>
    <option value="Campos do Jordão">Campos do Jordão</option>
    <option value="Canas">Canas</option>
    <option value="Caraguatatuba">Caraguatatuba</option>
    <option value="Cruzeiro">Cruzeiro</option>
    <option value="Cunha">Cunha</option>
    <option value="Guaratinguetá">Guaratinguetá</option>
    <option value="Igaratá">Igaratá</option>
    <option value="Jacareí">Jacareí</option>
    <option value="Jambeiro">Jambeiro</option>
    <option value="Lagoinha">Lagoinha</option>
    <option value="Lorena">Lorena</option>
    <option value="Monteiro Lobato">Monteiro Lobato</option>
    <option value="Natividade da Serra">Natividade da Serra</option>
    <option value="Paraibuna">Paraibuna</option>
    <option value="Pindamonhangaba">Pindamonhangaba</option>
    <option value="Piracaia">Piracaia</option>
    <option value="Potim">Potim</option>
    <option value="Queluz">Queluz</option>
    <option value="Redenção da Serra">Redenção da Serra</option>
    <option value="Roseira">Roseira</option>
    <option value="Santa Branca">Santa Branca</option>
    <option value="Santo Antônio do Pinhal">Santo Antônio do Pinhal</option>
    <option value="São Bento do Sapucaí">São Bento do Sapucaí</option>
    <option value="São José do Barreiro">São José do Barreiro</option>
    <option value="São José dos Campos">São José dos Campos</option>
    <option value="São Luiz do Paraitinga">São Luiz do Paraitinga</option>
    <option value="Silveiras">Silveiras</option>
    <option value="Taubaté">Taubaté</option>
    <option value="Tremembé">Tremembé</option>
    <option value="Ubatuba">Ubatuba</option>
    <option value="Outras">Outras</option>
</select>


            <button type="submit">Salvar Informações</button>
        </form>
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
