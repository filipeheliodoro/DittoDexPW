<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare('SELECT name, email FROM users WHERE id = :user_id');
    $stmt->execute([':user_id' => $userId]);
    $user = $stmt->fetch();
} catch (Exception $e) {
    echo "Erro ao buscar informações do usuário: " . $e->getMessage();
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM favorito WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($favorites as $key => $favorite) {
        $apiUrl = "https://pokeapi.co/api/v2/pokemon/" . strtolower($favorite['pokemon_name']);
        $pokemonData = file_get_contents($apiUrl);

        if ($pokemonData !== false) {
            $pokemonData = json_decode($pokemonData, true);
            $favorites[$key]['pokemon_image'] = $pokemonData['sprites']['front_default'] ?? null; 
        } else {
            $favorites[$key]['pokemon_image'] = null; 
        }
    }
} catch (PDOException $e) {
    echo "Erro ao buscar favoritos: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo "Erro ao buscar imagens dos Pokémon: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_id'])) {
    $favoriteId = $_POST['favorite_id'];

    try {
        $stmt = $pdo->prepare('DELETE FROM favorito WHERE id = :favorite_id AND user_id = :user_id');
        $stmt->execute([':favorite_id' => $favoriteId, ':user_id' => $userId]);

        header("Location: user.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao remover Pokémon dos favoritos: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['password'];

    try {
        $updateQuery = 'UPDATE users SET name = :name, email = :email';
        
        if (!empty($newPassword)) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery .= ', password = :password';
        }
        
        $updateQuery .= ' WHERE id = :user_id';

        $stmt = $pdo->prepare($updateQuery);
        $params = [
            ':name' => $newName,
            ':email' => $newEmail,
            ':user_id' => $userId
        ];

        if (!empty($newPassword)) {
            $params[':password'] = $newPasswordHash;
        }

        $stmt->execute($params);

        session_destroy();
        header("Location: ../login/login.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar perfil: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user.css">
    <title>Perfil do Usuário</title>
</head>
<body>
    <form method="POST" action="logout.php">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
    <h1>Editar Perfil</h1>

    <form method="POST" action="user.php">
        <div>
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div>
            <label for="password">Nova Senha (deixe em branco para não alterar):</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <button type="submit" name="update_profile">Atualizar</button>
        </div>
    </form>

    <h2>Pokémons Favoritos</h2>
    <div id="favorites-list">
        <?php if (!empty($favorites)): ?>
            <ul>
                <?php foreach ($favorites as $favorite): ?>
                    <li>
                        <img src="<?= htmlspecialchars($favorite['pokemon_image']) ?>" alt="<?= htmlspecialchars($favorite['pokemon_name']) ?>" width="100" height="100">
                        <strong><?= htmlspecialchars($favorite['pokemon_name']) ?></strong> (ID: <?= htmlspecialchars($favorite['pokemon_id']) ?>)
                        <form method="POST" action="user.php" style="display:inline;">
                            <input type="hidden" name="favorite_id" id="favorite_id" value="<?= htmlspecialchars($favorite['id']) ?>">
                            <button type="submit">Remover</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum Pokémon adicionado aos favoritos.</p>
        <?php endif; ?>
    </div>

    <form method="POST" action="../principal/dittodex.php">
        <button type="submit" class="voltar">Dittodex</button>
    </form>
    <form method="POST" action="../principal/equipa.php">
        <button type="submit" class="voltar">Equipa</button>
    </form>
    <form method="GET" action="../principal/battle_interface.php">
        <button type="submit" class="battle-btn">Iniciar Batalha</button>
    </form>
</body>
</html>
