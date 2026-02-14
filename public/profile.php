<?php 

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
        <input type="text" id="name" name="name" required><br><br>

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

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
