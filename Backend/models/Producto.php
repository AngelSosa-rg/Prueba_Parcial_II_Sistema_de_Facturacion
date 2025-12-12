<?php
require_once __DIR__ . "/../config/Database.php";

class Producto {
  private $pdo;

  public function __construct() {
    $this->pdo = Database::connect();
  }

  public function all() {
    return $this->pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll();
  }

  public function create($data) {
    $st = $this->pdo->prepare(
      "INSERT INTO productos(codigo,nombre,precio,stock,activo)
       VALUES (?,?,?,?,?)"
    );
    $st->execute([
      $data["codigo"] ?? null,
      $data["nombre"] ?? "",
      $data["precio"] ?? 0,
      $data["stock"] ?? 0,
      $data["activo"] ?? 1
    ]);
    return (int)$this->pdo->lastInsertId();
  }

  public function update($data) {
    $st = $this->pdo->prepare(
      "UPDATE productos SET codigo=?, nombre=?, precio=?, stock=?, activo=? WHERE id=?"
    );
    return $st->execute([
      $data["codigo"], $data["nombre"], $data["precio"],
      $data["stock"], $data["activo"], $data["id"]
    ]);
  }

  public function delete($id) {
    $st = $this->pdo->prepare("DELETE FROM productos WHERE id=?");
    return $st->execute([$id]);
  }
}
