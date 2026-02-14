<?php 

require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
}

if (empty($login) || empty($password)) {
        echo "Все поля обязательны для заполнения!";
        exit;
}

$stmt = $pdo->prepare(
        "SELECT * FROM users WHERE email = ? OR phone = ?"
    );
$stmt->execute([$login, $login]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь с таким логином не найден!";
    exit;
}

if ($user['password'] !== $password) {
    echo "Неверный пароль!";
    exit;
}

echo "Авторизация прошла успешно!";
    exit;

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
</head>
<body>
   <h2>Вход</h2>
   <form method="POST">
        <label>Телефон или эл.почта:</label>
        <input type="text" name="login" required><br><br>

        <label>Пароль:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Войти</button>
    </form> 
</body>
</html>
