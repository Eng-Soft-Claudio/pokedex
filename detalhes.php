<?php
include 'db/conexao.php';
session_start();

$pokemon = "";
$pokemonData = null;

if (isset($_GET['pokemon'])) {
    $pokemon = strtolower(trim($_GET['pokemon']));
    if (!empty($pokemon)) {
        $url = "https://pokeapi.co/api/v2/pokemon/$pokemon";
        $data = file_get_contents($url);
        $pokemonData = json_decode($data, true);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pokémon</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container text-center">
        <h1 class="my-5">Detalhes do Pokémon</h1>

        <?php if ($pokemonData): ?>
            <div class="card mx-auto my-4" style="width: 18rem;">
                <img src="<?php echo $pokemonData['sprites']['front_default']; ?>" class="card-img-top" alt="Imagem do Pokémon">
                <div class="card-body">
                    <h5 class="card-title"><?php echo ucfirst($pokemonData['name']); ?></h5>
                    <p class="card-text">Altura: <?php echo $pokemonData['height'] / 10; ?>m</p>
                    <p class="card-text">Peso: <?php echo $pokemonData['weight'] / 10; ?>kg</p>
                    <p class="card-text">Tipos:
                        <?php foreach ($pokemonData['types'] as $type): ?>
                            <?php echo ucfirst($type['type']['name']) . " "; ?>
                        <?php endforeach; ?>
                    </p>
                    <p class="card-text">Habilidades:
                        <?php foreach ($pokemonData['abilities'] as $ability): ?>
                            <?php echo ucfirst($ability['ability']['name']) . " "; ?>
                        <?php endforeach; ?>
                    </p>
                    <h6 class="card-subtitle mb-2 text-muted">Estatísticas:</h6>
                    <ul class="list-group">
                        <?php foreach ($pokemonData['stats'] as $stat): ?>
                            <li class="list-group-item"><?php echo ucfirst($stat['stat']['name']) . ": " . $stat['base_stat']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <p>Pokémon não encontrado ou inválido.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</body>
</html>
