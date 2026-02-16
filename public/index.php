<?php
session_start();

if (isset($_GET['register']) && $_GET['register'] === 'success') {
    echo "<p>Регистрация прошла успешно!</p>";
}

if (isset($_GET['login']) && $_GET['login'] === 'success') {
    echo "<p>Вы вошли в аккаунт!</p>";
}

$isAuthorized = isset($_SESSION['user_id']);

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
