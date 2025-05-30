<?php
include('../conexao.php');
include('header_user.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $usuario_id = $_SESSION['usuario_id']; // 👈 Aqui pegamos o ID do usuário

    $imagem = '';
    if (!empty($_FILES['imagem']['name'])) {
        $imagem = time() . '_' . $_FILES['imagem']['name'];
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../uploads/" . $imagem);
    }

    $sql = "INSERT INTO anuncios (titulo, descricao, preco, categoria, imagem, aprovado, data, usuario_id) 
            VALUES (?, ?, ?, ?, ?, 0, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $titulo, $descricao, $preco, $categoria, $imagem, $usuario_id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Anúncio enviado para aprovação!</p>";
    } else {
        echo "<p style='color:red;'>Erro ao criar anúncio.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input, select { margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .erro { color: red; text-align: center; margin-bottom: 10px; }
        .sucesso { color: green; text-align: center; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 10px; }
        .link a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
    <div class="container">
    <h2>Criar Anúncio</h2>
    <form method="POST" enctype="multipart/form-data" style="max-width:500px;  padding:15px; border:1px solid #ccc; border-radius:8px;">
        <label>Título:</label><br>
        <input type="text" name="titulo" required style="width:90%; padding:8px;"><br>

        <label>Descrição:</label><br>
        <textarea name="descricao" rows="4" required style="resize: none; width:90%; padding:8px;"></textarea><br>

        <label>Preço (R$):</label><br>  
        <input type="number" step="0.01" name="preco" placeholder="Digite o valor" required style="width:30%; padding:8px;"><br>

        <label>Categoria:</label><br>
        <select name="categoria" required style="width:30%; padding:8px;">
            <option value="">Selecione</option>
            <option value="Carro">Carro</option>
            <option value="Moto">Moto</option>
        </select><br>

        <label>Imagem:</label><br>
        <input type="file" name="imagem" id="imagemInput" style="width:90%; padding:8px;"><br>
        <img id="previewImagem" style="max-width:100%; max-height:200px; display:none; border:1px solid #ccc; border-radius:4px; margin-top:10px;"><br>
        
        <button type="submit" name="criar" style="padding:10px 20px; background:green; color:white; border:none; border-radius:5px;">Criar Anúncio</button>
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
</html>