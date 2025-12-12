<?php

class Database {

  public static function connect(): PDO {

    $host = "localhost";
    $db   = "facturacion";
    $user = "root";
    $pass = "";
    $charset = "utf8mb4";

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
      $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]);
      return $pdo;
    } catch (PDOException $e) {
      die("Error de conexión BD: " . $e->getMessage());
    }
  }
}
