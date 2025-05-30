<?php
include('../conexao.php');
include('header_user.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;

// Buscar os dados do anúncio
$sql = "SELECT * FROM anuncios WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    echo "<p>Anúncio não encontrado.</p>";
    exit();
}

$anuncio = $resultado->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $imagem = $anuncio['imagem']; // Manter imagem atual caso não seja enviada uma nova

    if (!empty($_FILES['imagem']['name'])) {
        $imagem = time() . '_' . $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../uploads/" . $imagem);
    }

    $sql = "UPDATE anuncios SET titulo = ?, descricao = ?, preco = ?, categoria = ?, imagem = ? WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssii", $titulo, $descricao, $preco, $categoria, $imagem, $id, $_SESSION['usuario_id']);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Anúncio atualizado com sucesso!</p>";
        header("Refresh:1; url=listar.php");
        exit();
    } else {
        echo "<p style='color:red;'>Erro ao atualizar anúncio.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 500px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input, select { margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .erro { color: red; text-align: center; margin-bottom: 10px; }
        .sucesso { color: green; text-align: center; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 5px}
        .link a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Anúncio</h2> 
        <form method="POST" enctype="multipart/form-data" style="max-width:500px;  padding:15px; border:1px solid #ccc; border-radius:8px;">

        <label>Título:</label><br>
        <input type="text" name="titulo" required style="width:90%; padding:8px;" value="<?php echo htmlspecialchars($anuncio['titulo']); ?>"><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" rows="4" required style="resize: none; width:90%; padding:8px;"><?php echo htmlspecialchars($anuncio['descricao']); ?></textarea><br>

        <label>Preço:</label><br>
        <input type="number" step="0.01" name="preco" required style="width: 20%; padding:8px;" value="<?php echo $anuncio['preco']; ?>"><br>

        <label>Categoria:</label><br>
        <select name="categoria" required style="width: 20%; padding:8px;"> 
            <option value="">Selecione</option>
            <option value="Carro" <?php echo ($anuncio['categoria'] == 'Carro') ? 'selected' : ''; ?>>Carro</option>
            <option value="Moto" <?php echo ($anuncio['categoria'] == 'Moto') ? 'selected' : ''; ?>>Moto</option>
        </select><br> 

        <label>Imagem Atual:</label><br>
        <?php if ($anuncio['imagem']) { ?>
            <img src="../uploads/<?php echo $anuncio['imagem']; ?>" alt="Imagem do anúncio" style="max-width:100%; height:auto;"><br><br>
        <?php } else { ?>
            <p>Nenhuma imagem enviada.</p>
        <?php } ?>

        <label>Nova Imagem (opcional):</label><br>
        <input type="file" name="imagem" id="imagemInput" style="width:90%; padding:8px;"><br>
        <img id="previewImagem" style="max-width:100%; max-height:200px; display:none; border:1px solid #ccc; border-radius:4px; margin-top:10px;"><br>

        <button type="submit" name="atualizar" style="padding:10px 20px; background:green; color:white; border:none; border-radius:5px;">Atualizar Anúncio</button>
        </form>
    </div>
</body>
<script>
document.getElementById('imagemInput').addEventListener('change', function(event) {
    const preview = document.getElementById('previewImagem');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
});
</script>