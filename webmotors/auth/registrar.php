<?php
include('conexao.php');

if (@$_REQUEST['botao'] == "Cadastrar") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = 'USER'; // Sempre USER

    $query = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES ('$nome', '$email', '$senha', '$tipo')";
    if (mysqli_query($conn, $query)) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário.";
    }
}
?>

<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="text" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <input type="submit" name="botao" value="Cadastrar">
</form>
