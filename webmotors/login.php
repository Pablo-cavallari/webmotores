<?php
session_start();
include('conexao.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome']; // Armazenar o nome também
            $_SESSION['tipo'] = $usuario['tipo'];

            if ($usuario['tipo'] === 'ADM') {
                header("Location: admin/menu.php");
            } else {
                header("Location: usuarios/menu.php");
            }
            exit();
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ccc; }
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input { margin-bottom: 10px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px; background: green; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .erro { color: red; text-align: center; }
        .link { text-align: center; margin-top: 10px; }
        .link a { text-decoration: none; color: blue; }
    </style>
</head>
<header>
    <div>
        <a href="index.php">WebMotors</a>
    </div>
</header>
<body>

<div class="container">
    <h2>Login</h2>
    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <div class="link">
        <p>Não tem uma conta? <a href="registrar.php">Cadastre-se aqui</a></p>
    </div>
</div>

</body>
</html>
