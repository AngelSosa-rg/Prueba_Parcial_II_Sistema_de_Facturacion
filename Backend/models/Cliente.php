<?php
require_once __DIR__ . "/../config/Database.php";

class Cliente {
  private $pdo;

  public function __construct() {
    $this->pdo = Database::connect();
  }

  public function all() {
    return $this->pdo->query("SELECT * FROM clientes ORDER BY id DESC")->fetchAll();
  }

  public function find($id) {
    $st = $this->pdo->prepare("SELECT * FROM clientes WHERE id=?");
    $st->execute([$id]);
    return $st->fetch();
  }

  public function create($data) {
    $st = $this->pdo->prepare("INSERT INTO clientes(cedula,nombres,email,telefono,direccion) VALUES (?,?,?,?,?)");
    $st->execute([
      $data["cedula"] ?? null,
      $data["nombres"] ?? "",
      $data["email"] ?? null,
      $data["telefono"] ?? null,
      $data["direccion"] ?? null
    ]);
    return (int)$this->pdo->lastInsertId();
  }

  public function update($data) {
    $st = $this->pdo->prepare("UPDATE clientes SET cedula=?, nombres=?, email=?, telefono=?, direccion=? WHERE id=?");
    return $st->execute([
      $data["cedula"], $data["nombres"], $data["email"], $data["telefono"], $data["direccion"], $data["id"]
    ]);
  }

  public function delete($id) {
    $st = $this->pdo->prepare("DELETE FROM clientes WHERE id=?");
    return $st->execute([$id]);
  }
}
