<?php
session_start();
$isAuthorized = isset($_SESSION['user_id']);

$message = null;

if (isset($_GET['register']) && $_GET['register'] === 'success') {
    $message = "Регистрация прошла успешно!";
}

if (isset($_GET['login']) && $_GET['login'] === 'success') {
    $message = "Выполнен вход в аккаунт!";
}

if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $message = "Осуществлён выход из аккаунта!";
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
</head>
<body>
    <h1>
       <a href="index.php">Главная страница</a> 
    </h1>

    <?php if ($message): ?>
        <p>
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <?php if (!$isAuthorized): ?>
        <p>
            <a href="register.php">Регистрация нового пользователя</a>
        </p>
        <p>
            <a href="login.php">Войти в аккаунт</a>
        </p>
    <?php else: ?>
        <p>
            <a href="profile.php">Профиль пользователя</a>
        </p>
        <p>
            <a href="logout.php">Выйти из аккаунта</a>
        </p>
    <?php endif; ?>
</body>
</html>
