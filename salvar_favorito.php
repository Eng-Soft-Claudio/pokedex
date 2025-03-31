<?php
include 'db/conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado para salvar favoritos!";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$pokemon = trim($_POST['pokemon']);

if (empty($pokemon)) {
    echo "Nome do Pokémon inválido!";
    exit();
}

$check_sql = "SELECT id FROM favoritos WHERE usuario_id = ? AND pokemon = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("is", $usuario_id, $pokemon);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "Este Pokémon já está nos seus favoritos!";
} else {
    $sql = "INSERT INTO favoritos (usuario_id, pokemon) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $pokemon);

    if ($stmt->execute()) {
        echo "Pokémon salvo nos favoritos!<br>";
        echo '<a href="visualizar_favoritos.php">Visualizar Favoritos</a><br>';
        echo '<a href="index.php">Voltar à Página Principal</a>';
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
}

$check_stmt->close();
?>
