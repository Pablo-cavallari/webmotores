<?php
include('conexao.php');
include('header.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>ID inválido.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM anuncios WHERE id = ? AND aprovado = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$anuncio = $result->fetch_assoc();

if (!$anuncio) {
    echo "<p>Anúncio não encontrado.</p>";
    exit;
}
?>
<header>
    <div style="display: flex; align-items: center; gap: 20px;">
        <a href="index.php">WebMotors</a>
    </div>
    <a href="login.php">Login</a>
</header>

<div class="container" style="margin-top: 30px;">
    <h2><?php echo htmlspecialchars($anuncio['titulo']); ?></h2>
    <?php if ($anuncio['imagem']) { ?>
        <img src="uploads/<?php echo htmlspecialchars($anuncio['imagem']); ?>" style="max-width: 100%; border-radius: 8px;">
    <?php } ?>
    <p><strong>Preço:</strong> R$ <?php echo number_format($anuncio['preco'], 2, ',', '.'); ?></p>
    <p><strong>Descrição:</strong> <?php echo nl2br(htmlspecialchars($anuncio['descricao'])); ?></p>
    <p><strong>Categoria:</strong> <?php echo htmlspecialchars($anuncio['categoria']); ?></p>
    <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($anuncio['data'])); ?></p>
</div>
