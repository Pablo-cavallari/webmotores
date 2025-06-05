<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] != 'USER') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>WebMotors</title>
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
        }
        .card {
            width: calc(33.33% - 20px);
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            box-sizing: border-box;
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
        }
        .card h3 {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .card p {
            margin: 5px 0;
        }
        @media (max-width: 768px) {
            .card {
                width: calc(50% - 20px);
            }
        }
        @media (max-width: 500px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h2>Usuário: <?php echo $_SESSION['nome']; ?></h2>
        <h2>Painel do Usuário</h2>

    <div class="links">
        <a href="menu.php">🏠 Painel</a>
        <a href='listar.php'>📄 Ver Anúncios</a>
        <a href='criar.php'>➕ Criar Novo Anúncio</a>
        <a href='../logout.php'>🚪 Sair</a>
    </div>
    </header>
</body>