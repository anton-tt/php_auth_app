<?php

require __DIR__ . '/../config/db.php';

$errors = [];

$name = '';
$phone = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'];
    $confirmPassword = $_POST['password_confirm'];

    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "Все поля обязательны для заполнения!";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Пароли не совпадают!";
    }

    if (empty($errors)) {    
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM users WHERE email = ? OR phone = ?"
        );
        $stmt->execute([$email, $phone]);
        $existingUser = $stmt->fetchColumn();

        if ($existingUser > 0) {
            $errors[] = "Пользователь с таким email или телефоном уже существует!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO users (name, phone, email,  password) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$name, $phone, $email, $hashedPassword]);
    
            header("Location: /index.php?register=success");
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
    <title>Регистрация нового пользователя</title>
</head>
<body>
    <h2>Регистрация</h2>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" 
            placeholder="Иван" required><br><br>

        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" 
            placeholder="+71234567890" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" 
            placeholder="ivan@mail.com" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" placeholder="******" required><br><br>

        <label for="password_confirm">Повторите пароль:</label>
        <input type="password" id="password_confirm" name="password_confirm" placeholder="******" required><br><br>

        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
