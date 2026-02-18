<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}
$userId = $_SESSION['user_id'];

require __DIR__ . '/../config/db.php';

$errors = [];
$message = null;

$name ='';
$phone = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($phone) || empty($email)) {
            $errors[] = "Все поля обязательны для заполнения!";
        } 
        
        if (empty($errors)) {
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM users WHERE (email = ? OR phone = ?) AND id != ?"
            );
            $stmt->execute([$email, $phone, $userId]);
            $existingUser = $stmt->fetchColumn();

            if ($existingUser > 0) {
                $errors[] = "Пользователь с таким email или телефоном уже существует!";
            } else {
                $stmt = $pdo->prepare(
                    "UPDATE users SET name = ?, phone = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?"
                );
                $stmt->execute([$name, $phone, $email, $userId]);
                $message = "Данные сохранены!";
            }
        }
    }        

    if (isset($_POST['update_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_new_password']; 

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $errors[] = "Все поля обязательны для заполнения!";
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors[] = "Пароли не совпадают!";
        } 
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $errors[] = "Пользователь не найден!";
            } elseif (!password_verify($currentPassword, $user['password'])) {
                $errors[] = "Неверно введён действующий пароль!";
            } else {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare(
                    "UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?"
                );
                $stmt->execute([$hashedNewPassword, $userId]);
                $message = "Пароль успешно изменён!";
            }
        }
    }  
      
} else {
    $stmt = $pdo->prepare(
        "SELECT name, email, phone FROM users WHERE id = ?"
    );
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        $errors[] = "Пользователь не найден!";
    } else {
        $name = $user['name'];
        $phone = $user['phone'];
        $email = $user['email'];
    }
}

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

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    <?php elseif ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <h3>Личные данные</h3>
    <form method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" 
            value="<?= htmlspecialchars($name) ?>" placeholder="Иван" required><br><br>

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" 
            value="<?= htmlspecialchars($phone) ?>" placeholder="+71234567890" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" 
            value="<?= htmlspecialchars($email) ?>" placeholder="ivan@mail.com" required><br><br>

        <button type="submit" name="update_profile">Сохранить данные</button>
    </form>

    <h3>Пароль</h3>
    <form method="POST">
        <label for="current_password">Текущий пароль:</label>
        <input type="password" id="current_password" name="current_password" placeholder="******" required><br><br>

        <label for="new_password">Новый пароль:</label>
        <input type="password" id="new_password" name="new_password" placeholder="******" required><br><br>

        <label for="confirm_new_password">Повтор нового пароля:</label>
        <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="******" required><br><br>

        <button type="submit" name="update_password">Сохранить пароль</button>
    </form>

</body>
</html>
