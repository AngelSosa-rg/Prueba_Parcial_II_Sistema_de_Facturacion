<?php
require_once __DIR__ . "/../config/Database.php";

class Reporte {
  private $pdo;

  public function __construct() {
    $this->pdo = Database::connect();
  }

  // Resumen por cliente
  public function resumen() {
    $sql = "
      SELECT
        c.id,
        c.nombres,
        COUNT(f.id) AS cantidad_facturas,
        COALESCE(SUM(f.subtotal),0) AS subtotal,
        COALESCE(SUM(f.iva),0) AS iva,
        COALESCE(SUM(f.total),0) AS total
      FROM clientes c
      LEFT JOIN facturas f ON f.cliente_id = c.id
      GROUP BY c.id, c.nombres
      ORDER BY total DESC, cantidad_facturas DESC, c.nombres ASC
    ";
    return $this->pdo->query($sql)->fetchAll();
  }

  // Detalle: facturas de un cliente (IMPORTANTE: incluye f.id)
  public function detalle($cliente_id) {
    $st = $this->pdo->prepare("
      SELECT
        f.id,
        f.numero,
        f.fecha,
        f.estado,
        f.subtotal,
        f.iva,
        f.total
      FROM facturas f
      WHERE f.cliente_id = ?
      ORDER BY f.id DESC
    ");
    $st->execute([(int)$cliente_id]);
    return $st->fetchAll();
  }
}
