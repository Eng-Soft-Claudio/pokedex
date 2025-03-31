<?php
include 'db/conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado para ver seus favoritos! <a href='login.php'>Faça login</a>";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT pokemon FROM favoritos WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Favoritos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container text-center">
        <h1 class="my-5">Seus Pokémon Favoritos</h1>
        <?php if ($result->num_rows > 0): ?>
            <ul class="list-group">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="list-group-item"><?php echo ucfirst($row['pokemon']); ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não tem Pokémon favoritos!</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</body>
</html>
