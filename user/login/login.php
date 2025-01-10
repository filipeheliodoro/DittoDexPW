<?php

require '../assets/database/db.php';

$error = "";

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        try {
        
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        
            if (!$user) {
                $error = "Usuário não encontrado.";
            } else {
              
                if (password_verify($password, $user['password'])) {
                
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name']; 

                  
                    header("Location: ../user/user.php"); 
                    exit;
                } else {
                    $error = "Senha incorreta.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erro ao conectar ao banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>LoginDitto</title>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form id="form" method="post" action="login.php">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit">Login</button>

         
            <?php if (!empty($error)): ?>
                <p id="error" class="error" style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </form>

        <div class="register-link">
            <p>Não possui uma conta? <a href="../registo/register.php">Registre-se aqui</a></p>
        </div>
    </div>
</body>
</html>
