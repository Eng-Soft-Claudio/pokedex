<?php
include 'db/conexao.php';
session_start();

$pokemon = "";
$pokemonData = null;
$errorMessage = "";

if (isset($_GET['search'])) {
    $pokemon = strtolower(trim($_GET['search']));
    if (!empty($pokemon)) {
        $url = "https://pokeapi.co/api/v2/pokemon/$pokemon";
        $data = file_get_contents($url);
        $pokemonData = json_decode($data, true);

        if (empty($pokemonData)) {
            $errorMessage = "Pokémon não encontrado!";
        }
    } else {
        $errorMessage = "Por favor, insira um nome ou ID válido.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Lobster&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container text-center">
        <h1 class="my-5">Pokédex</h1>

        <div class="theme-selector mb-4">
            <button id="light-mode" class="btn btn-outline-light">Modo Claro</button>
            <button id="dark-mode" class="btn btn-outline-dark">Modo Escuro</button>
        </div>

        <form method="GET" class="mb-4">
            <input type="text" name="search" class="form-control" placeholder="Nome ou ID do Pokémon" required>
            <button type="submit" class="btn btn-primary mt-2">Buscar</button>
        </form>

        <?php if (!empty($pokemonData)): ?>
            <div class="card mx-auto my-4" style="width: 18rem;">
                <img src="<?php echo $pokemonData['sprites']['front_default']; ?>" class="card-img-top" alt="Imagem do Pokémon">
                <div class="card-body">
                    <h5 class="card-title"><?php echo ucfirst($pokemonData['name']); ?></h5>
                    <p class="card-text">Altura: <?php echo $pokemonData['height'] / 10; ?>m</p>
                    <p class="card-text">Peso: <?php echo $pokemonData['weight'] / 10; ?>kg</p>

                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <form method="POST" action="salvar_favorito.php">
                            <input type="hidden" name="pokemon" value="<?php echo $pokemonData['name']; ?>">
                            <button type="submit" class="btn btn-success">Salvar nos Favoritos</button>
                        </form>
                    <?php else: ?>
                        <p><a href="login.php" class="btn btn-warning">Faça login</a> para salvar Pokémon favoritos.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="evolutions mt-4">
                <h5>Evoluções:</h5>
                <ul id="evolution-list"></ul>
            </div>
        <?php endif; ?>
        <div class="comparison mt-4">
            <h5>Comparar Pokémon</h5>
            <form id="comparison-form">
                <input type="text" id="pokemon1" class="form-control mb-2" placeholder="Primeiro Pokémon" required>
                <input type="text" id="pokemon2" class="form-control mb-2" placeholder="Segundo Pokémon" required>
                <button type="submit" class="btn btn-secondary">Comparar</button>
            </form>
            <div id="comparison-result" class="mt-3"></div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
