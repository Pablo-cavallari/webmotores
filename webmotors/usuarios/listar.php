<?php
include('../conexao.php');
include('header_user.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM anuncios WHERE usuario_id = ? AND aprovado = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<body>
    <div class="container">
        
        <h2>Meus Anúncios</h2>
        <div class="grid">
            <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
                <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($row['descricao']); ?></p>
                <p><strong>Preço:</strong> R$ <?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($row['categoria']); ?></p>
                <p><strong>Data do Anúncio:</strong> <?php echo date('d/m/Y H:i', strtotime($row['data'])); ?></p>
            <?php if (!empty($row['imagem'])): ?>
                <img src="../uploads/<?php echo htmlspecialchars($row['imagem']); ?>" alt="Imagem" style="max-width:200px;"><br><br>
            <?php endif; ?>
            <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a> |
            <a href="deletar.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este anúncio?');" style="color: red;">Excluir</a>   
            </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Você ainda não tem anúncios.</p>
<?php endif; ?>
        </div>
    </div>
</body>