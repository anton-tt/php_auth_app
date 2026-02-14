<?php 

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