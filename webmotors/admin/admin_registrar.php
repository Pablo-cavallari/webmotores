<?php
include('../conexao.php');
include('header_admin.php');

$sucesso = false; // controla se o cadastro deu certo

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    // Validar tipo
    if (isset($_POST['tipo']) && ($_POST['tipo'] === 'ADM' || $_POST['tipo'] === 'USER')) {
    $tipo = $_POST['tipo'];
    } else {
    $tipo = 'USER';
}

    // Verifica se o email já existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $erro = "Email já cadastrado. Por favor, tente outro.";
    } else {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

        if ($stmt->execute()) {
            $sucesso = true;
        } else {
            $erro = "Erro ao registrar usuário.";
        }
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
    <h2>Cadastro de Usuário</h2>
    
    <?php
    if (isset($erro)) {
        echo "<p class='erro'>$erro</p>";
    }
    if ($sucesso) {
        echo "<p class='sucesso'>Cadastro realizado com sucesso!</p>";
    }
    ?>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        
        <select name="tipo" required>
            <option value="USER">Usuário</option>
            <option value="ADM">Administrador</option>
        </select>
        
        <button type="submit">Cadastrar</button>
    </form>
</div>

</body>
</html>
