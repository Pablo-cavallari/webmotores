<?php
include('../conexao.php');
include('header_admin.php');

// Buscar anúncios pendentes
$result = mysqli_query($conn, "SELECT * FROM anuncios WHERE aprovado = 0");

if (isset($_GET['acao']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $acao = $_GET['acao'];

    if ($acao === 'aprovar') {
        $query = "UPDATE anuncios SET aprovado = 1 WHERE id = $id";
        mysqli_query($conn, $query);
        echo "<p style='color:green;'>Anúncio aprovado com sucesso!</p>";
    }

    if ($acao === 'recusar') {
        $query = "DELETE FROM anuncios WHERE id = $id";
        mysqli_query($conn, $query);
        echo "<p style='color:red;'>Anúncio recusado e removido!</p>";
    }

    // Recarregar a página
    header("Refresh:1; url=aprovar.php");
    exit;
}
?>

<h2>Anúncios Pendentes de Aprovação</h2>

<style>
    .grid-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 10px;
    }

    .card {
        flex: 1 1 250px;
        border: 1px solid #ccc;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        max-width: 300px;
    }

    .card img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin: 10px 0;
    }

    .card a {
        text-decoration: none;
        font-weight: bold;
    }

    .card a.aprovar {
        color: green;   
    }

    .card a.recusar {
        color: red;
        margin-left: 10px;
    }

    @media (max-width: 600px) {
        .card {
            flex: 1 1 100%;
        }
    }
</style>

<?php
if (mysqli_num_rows($result) == 0) {
    echo "<p>Sem anúncios pendentes no momento.</p>";
} else {
    echo "<div class='grid-container'>";
    while ($anuncio = mysqli_fetch_assoc($result)) {    
        echo "<div class='card'>";
        echo "<strong>Título:</strong> " . htmlspecialchars($anuncio['titulo']) . "<br>";
        echo "<strong>Descrição:</strong> " . htmlspecialchars($anuncio['descricao']) . "<br>";
        echo "<strong>Preço:</strong> R$ " . htmlspecialchars($anuncio['preco']) . "<br>";
        echo "<strong>Categoria:</strong> " . htmlspecialchars($anuncio['categoria']) . "<br>";
        echo "<strong>Data:</strong> " . htmlspecialchars($anuncio['data']) . "<br>";
        if (!empty($anuncio['imagem'])) {
            echo "<img src='../uploads/" . htmlspecialchars($anuncio['imagem']) . "' alt='Imagem'>";
        }
        echo "<a class='aprovar' href='aprovar.php?acao=aprovar&id=" . $anuncio['id'] . "'>✅ Aprovar</a>";
        echo "<a class='recusar' href='aprovar.php?acao=recusar&id=" . $anuncio['id'] . "'>❌ Recusar</a>";
        echo "</div>";
    }
    echo "</div>";
}
?>
