<?php

require 'db.php';


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

try {

    $stmt = $pdo->prepare('SELECT * FROM favorito WHERE user_id = :user_id');
    $stmt->execute([':user_id' => $userId]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao buscar favoritos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favoritos</title>
</head>
<body>
    <h1>Pokémons Favoritos</h1>
    <ul>
        <?php foreach ($favorites as $favorite): ?>
            <li><?= htmlspecialchars($favorite['pokemon_name']) ?> (ID: <?= $favorite['pokemon_id'] ?>)</li>
        <?php endforeach; ?>
    </ul>
    <a href="../index.php">Voltar</a>
</body>
</html>