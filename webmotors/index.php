<?php
include('conexao.php');
include('header.php');

// Buscar todos os anúncios aprovados
$sql = "SELECT * FROM anuncios WHERE aprovado = 1 ORDER BY data DESC";
$resultado = $conn->query($sql);
?>

<header>
    <div>
        <a href="index.php">WebMotors</a>
    </div>
    <a href="login.php">login</a>
</header>
<body>
<div class="container">
    <h2>Todos os Anúncios</h2>
    <div class="grid">
        <?php while ($anuncio = $resultado->fetch_assoc()) { ?>
            <div class="card">
                <?php if ($anuncio['imagem']) { ?>
                    <img src="uploads/<?php echo $anuncio['imagem']; ?>" alt="Imagem do anúncio">
                <?php } ?>
                <h3><?php echo htmlspecialchars($anuncio['titulo']); ?></h3>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($anuncio['descricao']); ?></p>
                <p><strong>Preço:</strong> R$ <?php echo number_format($anuncio['preco'], 2, ',', '.'); ?></p>
                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($anuncio['categoria']); ?></p>
                <p><strong>Data:</strong> <?php echo date('d/m/Y', strtotime($anuncio['data'])); ?></p>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
