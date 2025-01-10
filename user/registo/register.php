<?php
require '../assets/database/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Formato de email inválido.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $error = "Este email já está em uso.";
            } else {
                $userId = uniqid();

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$userId, $name, $email, $hashedPassword]);

                header("Location: ../login/login.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Registo de Utilizador</title>
</head>
<body>

    <div id="form-container">
        <h1>Registo de Utilizador</h1>

        <form id="form" method="post" action="register.php">
            <div class="input-container">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="name" required>
            </div>
            <div class="input-container">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-container">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
        
            <button type="submit">Registrar</button>
        
            <?php if (!empty($error)): ?>
                <p id="error" style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <p class="login-link">Já possui conta? <a href="../login/login.php">Login aqui</a></p>
        </form>
    </div>

</body>
</html>
