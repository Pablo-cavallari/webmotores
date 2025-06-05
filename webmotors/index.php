<?php
include('conexao.php');
include('header.php');

$condicoes = ["aprovado = 1"];
$parametros = [];

if (!empty($_GET['nome'])) {
    $condicoes[] = "titulo LIKE ?";
    $parametros[] = "%" . $_GET['nome'] . "%";
}

// Filtro por valor
if (isset($_GET['valor']) && $_GET['valor'] !== '') {
    $condicoes[] = "preco <= ?";
    $parametros[] = $_GET['valor'];
}

if (!empty($_GET['tipo'])) {
    $condicoes[] = "categoria = ?";
    $parametros[] = $_GET['tipo'];
}

$sql = "SELECT * FROM anuncios";
if (!empty($condicoes)) {
    $sql .= " WHERE " . implode(" AND ", $condicoes);
}
$sql .= " ORDER BY data DESC";

$stmt = $conn->prepare($sql);

if (!empty($parametros)) {
    $tipos = '';
    foreach ($parametros as $param) {
        $tipos .= is_numeric($param) ? 'd' : 's';
    }
    $stmt->bind_param($tipos, ...$parametros);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<header>
    <div style="display: flex; align-items: center; gap: 20px;">
        <a href="index.php">WebMotors</a>
        <form method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="nome" placeholder="Buscar por nome" 
                   style="padding: 6px 10px; border: none; border-radius: 4px;">
            <input type="number" name="valor" placeholder="Valor máximo" oninput="formatarValor(this)"  style="min-width: 130px; padding: 6px;"
                   style="padding: 6px 10px; border: none; border-radius: 4px; width: 120px;"> 
            <select name="tipo" 
                    style="padding: 6px 10px; border: none; border-radius: 4px;">
                <option value="">Todos os tipos</option>
                <option value="Carro">Carro</option>
                <option value="Moto">Moto</option>
            </select>
            <button type="submit" 
                    style="padding: 6px 14px; background-color:rgb(250, 11, 11); color: white; border: none; border-radius: 4px; cursor: pointer;">
                Pesquisar
            </button>
        </form>
    </div>
    <a href="login.php">Login</a>
</header>

<body>
<div class="container">
    <h2>Todos os Anúncios</h2>
    <div class="grid">
        <?php if ($resultado->num_rows === 0): ?>
            <p style="text-align:center; color:red;">Nenhum anúncio encontrado com os filtros aplicados.</p>
        <?php else: ?>
            <?php while ($anuncio = $resultado->fetch_assoc()) { ?>
                <a href="anuncio.php?id=<?php echo $anuncio['id']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <?php if (!empty($anuncio['imagem'])) { ?>
                            <img src="uploads/<?php echo htmlspecialchars($anuncio['imagem']); ?>" alt="Imagem do anúncio">
                        <?php } ?>
                        <h3><?php echo htmlspecialchars($anuncio['titulo']); ?></h3>
                        <p><strong>R$ <?php echo number_format($anuncio['preco'], 2, ',', '.'); ?></strong></p>
                    </div>
                </a>
            <?php } ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #333;
        color: white;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    header a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
    }

    .grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
    }

    .card {
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        box-sizing: border-box;
        text-align: center;
        transition: transform 0.2s;
        background: white;
    }

    .card:hover {
        transform: scale(1.02);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 4px;
    }

    .card h3 {
        margin: 10px 0 5px;
        font-size: 1.1em;
        color: #222;
    }

    .card p {
        margin: 5px 0;
        font-weight: bold;
        color: #000;
    }

    @media (max-width: 768px) {
        .card {
            width: 48%;
        }
    }

    @media (max-width: 500px) {
        .card {
            width: 100%;
        }
    }
</style>

