<?php

$host = 'localhost';     
$db   = 'pokemon';        
$user = 'yuri';           
$pass = 'YuriNeves';               
$charset = 'utf8mb4';    


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     
    PDO::ATTR_EMULATE_PREPARES   => false,                 
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>