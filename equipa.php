<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require '../assets/database/db.php'; 

function getRandomPokemons($count = 50) {
    $pokemons = [];
    $pokemonIds = array_rand(range(1, 999), $count); 

    foreach ($pokemonIds as $id) {
        $apiUrl = "https://pokeapi.co/api/v2/pokemon/" . ($id + 1);
        $pokemonData = file_get_contents($apiUrl);
        
        if ($pokemonData !== false) {
            $pokemonData = json_decode($pokemonData, true);
            $pokemons[] = [
                'id' => $id,
                'name' => ucfirst($pokemonData['name']),
                'image' => $pokemonData['sprites']['front_default'] ?? null
            ];
        }
    }

    return $pokemons;
}

function isPokemonTaken($pokemonId, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM Equipa WHERE id_pokemon = ?");
    $stmt->execute([$pokemonId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!isset($_SESSION['user_team'])) {
    $_SESSION['user_team'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_team'])) {
    $_SESSION['user_team'] = [];
    header("Location: equipa.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pokemon'])) {
    $pokemonId = $_POST['pokemon_id'];
    $pokemonName = $_POST['pokemon_name'];
    $pokemonImage = $_POST['pokemon_image'];

    if (isPokemonTaken($pokemonId, $pdo)) {
        $_SESSION['message'] = "Este Pokémon já foi adicionado por outro utilizador!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO Equipa (id_pokemon, nome_pokemon, nome_utilizadores) VALUES (?, ?, ?)");
        $stmt->execute([$pokemonId, $pokemonName, $_SESSION['user_id']]);
    
        $_SESSION['user_team'][] = [
            'id' => $pokemonId,
            'name' => $pokemonName,
            'image' => $pokemonImage
        ];
    }
    
    header("Location: equipa.php");
    exit;
}

$randomPokemons = getRandomPokemons();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="equipa.css">
    <link rel="shortcut icon" href="../img/ditto.png" type="image/x-icon">
    <title>Dittodex</title>
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="notification">
            <?= htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <nav class="navbar">
        <div class="nav-container">
            <a href="dittodex.php" class="nav-item link">Dittodex</a>
            <a class="nav-item link active">Equipa</a>
            <a href="../user/user.php" class="nav-item link">Perfil</a>
        </div>
    </nav>
    <h1>Escolha sua Equipa Pokémon</h1>

    <?php if (count($_SESSION['user_team']) < 6): ?>
        <p>Você pode adicionar mais <?= 6 - count($_SESSION['user_team']) ?> Pokémon à sua equipe.</p>
    <?php else: ?>
        <p><strong>Sua equipe está completa!</strong></p>
    <?php endif; ?>

    <form method="POST" action="equipa.php">
        <button type="submit" name="reset_team">Recomeçar Equipe</button>
    </form>

    <h2>Sua Equipe</h2>
    <div class="team">
        <?php if (!empty($_SESSION['user_team'])): ?>
            <ul>
                <?php foreach ($_SESSION['user_team'] as $pokemon): ?>
                    <li>
                        <img src="<?= htmlspecialchars($pokemon['image']) ?>" alt="<?= htmlspecialchars($pokemon['name']) ?>">
                        <?= htmlspecialchars($pokemon['name']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum Pokémon na equipe ainda.</p>
        <?php endif; ?>
    </div>

    <h2>Pokémons Disponíveis</h2>
    <table>
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($randomPokemons as $pokemon): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($pokemon['image']) ?>" alt="<?= htmlspecialchars($pokemon['name']) ?>">
                    </td>
                    <td><?= htmlspecialchars($pokemon['name']) ?></td>
                    <td>
                        <?php if (count($_SESSION['user_team']) < 6): ?>
                            <form method="POST" action="equipa.php">
                                <input type="hidden" name="pokemon_id" value="<?= htmlspecialchars($pokemon['id']) ?>">
                                <input type="hidden" name="pokemon_name" value="<?= htmlspecialchars($pokemon['name']) ?>">
                                <input type="hidden" name="pokemon_image" value="<?= htmlspecialchars($pokemon['image']) ?>">
                                <button type="submit" name="add_pokemon">Adicionar à Equipa</button>
                            </form>
                        <?php else: ?>
                            <button disabled>Equipe Completa</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
