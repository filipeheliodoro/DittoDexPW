<?php
session_start();

$searchedPokemon = $_SESSION['searched_pokemon'] ?? null;
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

<form action="../principal/dittodex.php">
    <input type="submit" value="Voltar" />
</form>
</body>
</html>
