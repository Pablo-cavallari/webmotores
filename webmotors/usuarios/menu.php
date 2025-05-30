<?php
include('header_user.php');
include('../conexao.php');


// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// Pega as informações do usuário
$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT nome, email, tipo FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Menu do Usuário</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        a { text-decoration: none; color: blue; margin: 5px; display: inline-block; }
        .info { margin: 15px 0; }   
    </style>
</head>
<body>
    <h2>Bem-vindo <?php echo htmlspecialchars($user['nome']); ?></h2>
    <div class="info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Tipo de usuário:</strong> <?php echo htmlspecialchars($user['tipo']); ?></p>
    </div>
</body>
</html>