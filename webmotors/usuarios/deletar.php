<?php
include('../conexao.php');
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Pega a imagem para deletar do servidor
    $sql = "SELECT imagem FROM anuncios WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $anuncio = $result->fetch_assoc();

    if ($anuncio) {
        // Exclui a imagem se existir
        if (!empty($anuncio['imagem']) && file_exists("../uploads/" . $anuncio['imagem'])) {
            unlink("../uploads/" . $anuncio['imagem']);
        }

        // Exclui o anúncio do banco
        $delete = $conn->prepare("DELETE FROM anuncios WHERE id = ? AND usuario_id = ?");
        $delete->bind_param("ii", $id, $usuario_id);
        $delete->execute();
    }
}

header("Location: listar.php"); // redireciona para a página de anúncios
exit();
?>
