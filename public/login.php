<?php
session_start();

require __DIR__ . '/../config/db.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $error = "Все поля обязательны для заполнения!";
    } else {
        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE email = ? OR phone = ?"
        );
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "Пользователь с таким логином не найден!";
        } elseif (!password_verify($password, $user['password'])) {
            $error = "Неверный пароль!";
        } else {
            $_SESSION['user_id'] = $user['id'];
            header("Location: /index.php?login=success");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация пользователя</title>
</head>
<body>
   <h2>Вход</h2>

    <?php if ($error): ?>
        <p>
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

   <form method="POST">
        <label for="login">Телефон или эл.почта:</label>
        <input type="text" id="login" name="login" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Войти</button>
    </form> 
</body>
</html>
