<?php

require 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $pokemonId = $_POST['pokemon_id'] ?? null;
    $pokemonName = $_POST['pokemon_name'] ?? null;

    if ($action && $pokemonId) {
        try {
            if ($action === 'add' && $pokemonName) {
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM favorito WHERE user_id = :user_id AND pokemon_id = :pokemon_id');
                $stmt->execute([
                    ':user_id' => $userId,
                    ':pokemon_id' => $pokemonId
                ]);
                $count = $stmt->fetchColumn();

                if ($count == 0) {
                    $stmt = $pdo->prepare('INSERT INTO favorito (user_id, pokemon_id, pokemon_name) VALUES (:user_id, :pokemon_id, :pokemon_name)');
                    $stmt->execute([
                        ':user_id' => $userId,
                        ':pokemon_id' => $pokemonId,
                        ':pokemon_name' => $pokemonName
                    ]);
                }
            } elseif ($action === 'remove') {
                $stmt = $pdo->prepare('DELETE FROM favorito WHERE user_id = :user_id AND pokemon_id = :pokemon_id');
                $stmt->execute([
                    ':user_id' => $userId,
                    ':pokemon_id' => $pokemonId
                ]);
            }

            header("Location: dittodex.php");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao gerenciar favoritos: " . $e->getMessage();
        }
    } else {
        echo "Dados inválidos.";
    }
} else {
    echo "Método inválido.";
}
?>