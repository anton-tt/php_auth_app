<?php

$host = '127.0.0.1';
$port = '5432';
$dbname = 'php_auth_app';
$user = 'php_auth_user';
$password = '123456';

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}
