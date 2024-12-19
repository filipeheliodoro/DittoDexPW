<?php

require 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pokemonId = $_POST['pokemon_id'] ?? null;
    $pokemonName = $_POST['pokemon_name'] ?? null;

    if ($pokemonId && $pokemonName) {
        try {

            $stmt = $pdo->prepare('INSERT INTO favorito (user_id, pokemon_id, pokemon_name) VALUES (:user_id, :pokemon_id, :pokemon_name)');
            $stmt->execute([
                ':user_id' => $userId,
                ':pokemon_id' => $pokemonId,
                ':pokemon_name' => $pokemonName
            ]);

            header("Location: listaPokemon.php?success=1");
            exit;

        } catch (PDOException $e) {
            echo "Erro ao adicionar favorito: " . $e->getMessage();
        }
    } else {
        echo "Dados inválidos.";
    }
} else {
    echo "Método inválido.";
}