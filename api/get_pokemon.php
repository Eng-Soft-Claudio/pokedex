<?php
include 'db/conexao.php';

$pokemon = "";
$pokemonData = null;

if (isset($_GET['pokemon'])) {
    $pokemon = strtolower(trim($_GET['pokemon']));
    if (!empty($pokemon)) {
        $url = "https://pokeapi.co/api/v2/pokemon/$pokemon";
        $data = file_get_contents($url);
        $pokemonData = json_decode($data, true);

        if ($pokemonData) {
            header('Content-Type: application/json');
            echo json_encode($pokemonData);
        } else {
            echo json_encode(["error" => "Pokémon não encontrado"]);
        }
    } else {
        echo json_encode(["error" => "Nome ou ID do Pokémon inválido"]);
    }
} else {
    echo json_encode(["error" => "Parâmetro 'pokemon' não fornecido"]);
}
?>
