<?php

require '../assets/database/db.php';

session_start(); 

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

if (isset($_POST['pokemon-name']) && !empty($_POST['pokemon-name'])) {
    $pokemonDetails = fetchPokemonByName($_POST['pokemon-name']);
    if ($pokemonDetails) {
        $_SESSION['searched_pokemon'] = $pokemonDetails;
    }
    header("Location: ../pesquisados/pesquisa_pokemon.php");
    exit;
}

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
        'shiny_image' => $data['sprites']['front_shiny'],
        'types' => array_map(function($type) {
            return $type['type']['name'];
        }, $data['types']),
        'abilities' => array_map(function($ability) {
            return $ability['ability']['name'];
        }, $data['abilities']),
        'stats' => $data['stats'],
        'base_experience' => $data['base_experience'],
        'weight' => $data['weight'],
        'height' => $data['height'],
        'moves' => array_map(function($move) {
            return $move['move']['name'];
        }, $data['moves']),
    ];
}

function fetchPokemonsWithDetails($page = 1, $itemsPerPage = 15) {
    $offset = ($page - 1) * $itemsPerPage;

    $url = "https://pokeapi.co/api/v2/pokemon?offset=$offset&limit=$itemsPerPage";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return null;
    }

    $pokemonList = json_decode($response, true)['results'];

    $detailedPokemons = [];
    foreach ($pokemonList as $pokemon) {
        $details = fetchPokemonDetails($pokemon['url']);
        if ($details) {
            $detailedPokemons[] = $details;
        }
    }

    return $detailedPokemons;
}

function fetchPokemonDetails($url) {
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
        'shiny_image' => $data['sprites']['front_shiny'],
        'types' => array_column($data['types'], 'type', 'name'),
    ];
}

$searchedPokemon = null;
if (isset($_POST['pokemon-name']) && !empty($_POST['pokemon-name'])) {
    $searchedPokemon = fetchPokemonByName($_POST['pokemon-name']);
}

if (!$searchedPokemon) {
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $pokemons = fetchPokemonsWithDetails($currentPage);
    $totalPokemons = 1118; 
    $maxPages = ceil($totalPokemons / 15);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dittodex.css">
    <link rel="shortcut icon" href="../img/ditto.png" type="image/x-icon">
    <title>DittoDex</title>
</head>
<body>
    <nav class="navbar">
        <form class="logout-form" method="POST" action="logout.php">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
        <div class="nav-container">
            <a class="nav-item link active">Dittodex</a>
            <a href="equipa.php" class="nav-item link">Equipa</a>
            <a href="../user/user.php" class="nav-item link">Perfil</a>
        </div>
    </nav>
    <div class="container">
    <h1>DittoDex</h1>
        <form method="POST" class="form-container">
            <input type="text" name="pokemon-name" placeholder="Digite o nome do Pokémon" required>
            <button type="submit">Procurar</button>
        </form>

        <?php if ($searchedPokemon): ?>
      
            <h2>Resultado da Pesquisa</h2>
            <div id="pokemon-result">
                <p><strong>ID:</strong> <?= $searchedPokemon['id'] ?></p>
                <p><strong>Nome:</strong> <?= ucfirst($searchedPokemon['name']) ?></p>
                <img src="<?= $searchedPokemon['image'] ?>" alt="<?= $searchedPokemon['name'] ?>">
                <p><strong>Tipos:</strong> <?= implode(', ', array_map('ucfirst', $searchedPokemon['types'])) ?></p>
            </div>
        <?php else: ?>
       
            <h2>Lista de Pokémons</h2>
            <div id="pokemon-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Imagem</th>
                            <th>Tipos</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pokemons)): ?>
                            <?php foreach ($pokemons as $pokemon): ?>
                                <tr>
                                    <td><?= $pokemon['id'] ?></td>
                                    <td><?= ucfirst($pokemon['name']) ?></td>
                                    <td class="pokemon-cell">
                                        <div class="pokemon-img-container">
                                            <img src="<?= $pokemon['image'] ?>" 
                                                alt="<?= $pokemon['name'] ?>" 
                                                class="pokemon-normal">
                                            <img src="<?= $pokemon['shiny_image'] ?>" 
                                                alt="<?= $pokemon['name'] ?>" 
                                                class="pokemon-shiny">
                                        </div>
                                    </td>
                                    <td>
                                        <?= implode(', ', array_map(function($type) {
                                            return ucfirst($type['name']);
                                        }, $pokemon['types'])) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorito WHERE user_id = :user_id AND pokemon_id = :pokemon_id");
                                        $stmt->execute([
                                            ':user_id' => $userId,
                                            ':pokemon_id' => $pokemon['id']
                                        ]);
                                        $isFavorite = $stmt->fetchColumn() > 0;
                                        ?>
                                    <form method="POST" action="favoritos.php" style="display: <?= $isFavorite ? 'none' : 'inline-block' ?>;">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="pokemon_id" value="<?= $pokemon['id'] ?>">
                                        <input type="hidden" name="pokemon_name" value="<?= $pokemon['name'] ?>">
                                        <button type="submit" class="btn-favorite" title="Adicionar aos Favoritos">
                                            <i class="bi bi-bookmark-star"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="favoritos.php" style="display: <?= $isFavorite ? 'inline-block' : 'none' ?>;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="pokemon_id" value="<?= $pokemon['id'] ?>">
                                        <button type="submit" class="btn-favorite" title="Remover dos Favoritos">
                                            <i class="bi bi-bookmark-star-fill"></i>
                                        </button>
                                    </form>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Nenhum Pokémon encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
            <div class="page-info">Página <?= $currentPage ?> de <?= $maxPages ?></div>
            <div class="pagination-buttons">
                <a href="?page=<?= max(1, $currentPage - 1) ?>" class="pagination-btn">Anterior</a>
                <a href="?page=<?= min($maxPages, $currentPage + 1) ?>" class="pagination-btn">Próximo</a>
            </div>
                <form method="GET" class="page-jump-form">
                <label for="page-number">Ir para a página:</label>
                <input type="number" id="page-number" name="page" min="1" max="<?= $maxPages ?>" value="<?= $currentPage ?>" required>
            <button type="submit" class="pagination-btn">Ir</button>
                </form>
            </div>
            <div class="logout-container">
            </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
