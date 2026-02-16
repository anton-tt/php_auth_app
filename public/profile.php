<?php 
session_start();

$userId = $_SESSION['user_id'];

require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        if (empty($name) || empty($phone) || empty($email)) {
            echo "Все поля обязательны для заполнения!";
            exit;
        }

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM users WHERE (email = ? OR phone = ?) AND id != ?"
        );
        $stmt->execute([$email, $phone, $userId]);
        $existingUser = $stmt->fetchColumn();
        if ($existingUser > 0) {
            echo "Пользователь с таким email или телефоном уже существует!";
            exit;
        }

        $stmt = $pdo->prepare(
            "UPDATE users SET name = ?, phone = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?"
        );
        $stmt->execute([$name, $phone, $email, $userId]);
        echo "Данные сохранены!";
    }

    if (isset($_POST['update_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_new_password']; 

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo "Все поля обязательны для заполнения!";
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            echo "Пароли не совпадают!";
            exit;
        }

        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user) {
            echo "Пользователь не найден!";
            exit;
        }
        if (!password_verify($currentPassword, $user['password'])) {
            echo "Неверно введён действующий пароль!";
            exit;
        }
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?"
        );
        $stmt->execute([$hashedNewPassword, $userId]);
        echo "Пароль успешно изменён!";

    }
}

$stmt = $pdo->prepare(
        "SELECT name, email, phone FROM users WHERE id = ?"
);
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "Пользователь не найден!";
    exit;
}

$name = $user['name'];
$phone = $user['phone'];
$email = $user['email'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование данных пользователя</title>
</head>
<body>
    <h2>Профиль пользователя</h2>
    
    <h3>Личные данные</h3>
    <form method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" 
            value="<?= htmlspecialchars($name) ?>" required><br><br>

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" 
            value="<?= htmlspecialchars($phone) ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" 
            value="<?= htmlspecialchars($email) ?>" required><br><br>

        <button type="submit" name="update_profile">Сохранить данные</button>
    </form>

    <h3>Пароль</h3>
    <form method="POST">
        <label for="current_password">Текущий пароль:</label>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">Новый пароль:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_new_password">Повтор нового пароля:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" required><br><br>

        <button type="submit" name="update_password">Сохранить пароль</button>
    </form>

</body>
</html>
