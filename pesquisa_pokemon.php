<?php
session_start(); 


function fetchPokemonByName($name) {
    $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower(trim($name));
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return null;
    }

    $data = json_decode($response, true);

    return [
        'id' => $data['id'],
        'name' => $data['name'],
        'image' => $data['sprites']['front_default'],
        'types' => array_column($data['types'], 'type', 'name'),
    ];
}


function getPokemonLocations($pokemonId) {
    $locationUrl = "https://pokeapi.co/api/v2/pokemon/$pokemonId/encounters";

    $response = file_get_contents($locationUrl);
    return json_decode($response, true);
}


$searchedPokemon = null;
if (isset($_SESSION['searched_pokemon'])) {
    $pokemonName = $_SESSION['searched_pokemon'];
    unset($_SESSION['searched_pokemon']); 
    $searchedPokemon = fetchPokemonByName($pokemonName);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pesquisa_pokemon.css">
    <title>Detalhes do Pokémon</title>
</head>
<body>
<h1>Detalhes do Pokemon</h1>

    <?php if ($searchedPokemon): ?>
        <div id="pokemon-info">
            <h2><?= ucfirst($searchedPokemon['name']) ?></h2>
            <img src="<?= $searchedPokemon['image'] ?>" alt="<?= $searchedPokemon['name'] ?>" />
            <p><strong>ID:</strong> #<?= $searchedPokemon['id'] ?></p>
            <p><strong>Tipos:</strong> <?= implode(', ', array_map('ucfirst', $searchedPokemon['types'])) ?></p>
        </div>
    <?php elseif ($pokemon): ?>
        <div id="pokemon-info">
            <h2><?= ucfirst($pokemon['name']) ?></h2>
            <img src="<?= $pokemon['sprites']['front_default'] ?>" alt="<?= $pokemon['name'] ?>" />
            <p><strong>ID:</strong> #<?= $pokemon['id'] ?></p>
            <p><strong>Tipos:</strong> <?= implode(', ', array_map('ucfirst', $pokemon['types'])) ?></p>
            <h3>Localizações</h3>
            <?php if (!empty($locations)): ?>
                <ul>
                    <?php foreach ($locations as $location): ?>
                        <li>
                            <strong><?= ucfirst($location['location_area']['name']) ?></strong>
                            <?php foreach ($location['version_details'] as $version): ?>
                                <?= ucfirst($version['version']['name']) ?> (Chance: <?= $version['encounter_details'][0]['chance'] ?>%)
                            <?php endforeach; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Sem informações de localização.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Pokémon não encontrado. Tente novamente.</p>
    <?php endif; ?>

    <form action="../principal/dittodex.php">
        <input type="submit" value="Voltar" />
    </form>
</body>
</html>
