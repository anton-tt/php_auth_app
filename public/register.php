<?php

require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirm'];

    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($passwordConfirm)) {
        echo "Все поля обязательны для заполнения!";
        exit;
    }

    if ($password !== $passwordConfirm) {
        echo "Пароли не совпадают!";
        exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO users (name, phone, email,  password) VALUES (?, ?, ?, ?)"
    );

    $stmt->execute([$name, $phone, $email, $password]);

    echo "Данные отправлены!";
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация нового пользователя</title>
</head>
<body>
    <h2>Регистрация</h2>
    <form method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="password_confirm">Повторите пароль:</label>
        <input type="password" id="password_confirm" name="password_confirm" required><br><br>

        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
