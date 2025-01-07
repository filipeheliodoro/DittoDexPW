<?php
require_once '../assets/database/db.php';
session_start();

$userId = $_SESSION['user_id'];
$searchedPokemon = $_SESSION['searched_pokemon'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pesquisa_pokemon.css">
    <link rel="shortcut icon" href="../img/ditto.png" type="image/x-icon">
    <title>Dittodex</title>
</head>
<body>
    <nav class="navbar">
        <a href="../principal/dittodex.php" class="logout-btn">Voltar</a>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorito WHERE user_id = :user_id AND pokemon_id = :pokemon_id");
        $stmt->execute([
            ':user_id' => $userId,
            ':pokemon_id' => $searchedPokemon['id']
        ]);
        $isFavorite = $stmt->fetchColumn() > 0;
        ?>
        <form method="POST" action="../principal/favoritos.php" style="display: <?= $isFavorite ? 'none' : 'inline-block' ?>;">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="keepInPage" value="true">
            <input type="hidden" name="pokemon_id" value="<?= $searchedPokemon['id'] ?>">
            <input type="hidden" name="pokemon_name" value="<?= $searchedPokemon['name'] ?>">
            <button type="submit" class="btn-favorite" title="Adicionar aos Favoritos">
                <i class="bi bi-bookmark-star"></i>
                <p>Adicionar Favorito</p>
            </button>
        </form>
        <form method="POST" action="../principal/favoritos.php" style="display: <?= $isFavorite ? 'inline-block' : 'none' ?>;">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="keepInPage" value="true">
            <input type="hidden" name="pokemon_id" value="<?= $searchedPokemon['id'] ?>">
            <button type="submit" class="btn-favorite" title="Remover dos Favoritos">
                <i class="bi bi-bookmark-star-fill"></i>
                <p>Remover Favorito</p>
            </button>
        </form>
    </nav>
    <h1>Detalhes do Pokémon</h1>

<?php if ($searchedPokemon): ?>
    <div id="pokemon-info">
        <h2><?= ucfirst($searchedPokemon['name']) ?></h2>
        <img src="<?= $searchedPokemon['image'] ?>" alt="<?= $searchedPokemon['name'] ?>" />
        <p><strong>ID:</strong> #<?= $searchedPokemon['id'] ?></p>
        <p><strong>Tipos:</strong> <?= implode(', ', array_map('ucfirst', $searchedPokemon['types'])) ?></p>
        <p><strong>Habilidades:</strong> <?= implode(', ', array_map('ucfirst', $searchedPokemon['abilities'])) ?></p>
        <p><strong>Altura:</strong> <?= $searchedPokemon['height'] / 10 ?> m</p>
        <p><strong>Peso:</strong> <?= $searchedPokemon['weight'] / 10 ?> kg</p>
        <p><strong>Experiência Base:</strong> <?= $searchedPokemon['base_experience'] ?></p>
        
        <h3>Estatísticas</h3>
        <ul>
            <?php foreach ($searchedPokemon['stats'] as $stat): ?>
                <li><?= ucfirst($stat['stat']['name']) ?>: <?= $stat['base_stat'] ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Ataques</h3>
        <ul>
            <?php foreach ($searchedPokemon['moves'] as $move): ?>
                <li><?= ucfirst($move) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <p>Pokémon não encontrado. Tente novamente.</p>
<?php endif; ?>
</body>
</html>
