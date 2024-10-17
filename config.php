<?php
    $host = 'localhost';
    $db = 'bd_nubeico';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("No se pudo conectar a la base de datos: " . $e->getMessage());
    }
?>