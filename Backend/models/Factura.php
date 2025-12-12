<?php
require_once __DIR__ . "/../config/Database.php";

class Factura {
  private $pdo;

  public function __construct() {
    $this->pdo = Database::connect();
  }

  public function all() {
    $sql = "
      SELECT f.*, c.nombres AS cliente
      FROM facturas f
      JOIN clientes c ON c.id = f.cliente_id
      ORDER BY f.id DESC
    ";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function create($data) {
    $this->pdo->beginTransaction();

    $cliente_id = (int)($data["cliente_id"] ?? 0);
    $fecha = $data["fecha"] ?? date("Y-m-d");
    $items = $data["items"] ?? [];

    if ($cliente_id <= 0) throw new Exception("cliente_id inválido");
    if (!is_array($items) || count($items) === 0) throw new Exception("items vacío");

    // calcular subtotal con precios de BD
    $subtotal = 0;
    $getProd = $this->pdo->prepare("SELECT id, nombre, precio, activo, stock FROM productos WHERE id=?");

    foreach ($items as $i) {
      $pid = (int)($i["producto_id"] ?? 0);
      $cant = (int)($i["cantidad"] ?? 0);
      if ($pid <= 0 || $cant <= 0) throw new Exception("Item inválido");

      $getProd->execute([$pid]);
      $p = $getProd->fetch();
      if (!$p) throw new Exception("Producto no existe (ID $pid)");
      if ((int)$p["activo"] !== 1) throw new Exception("Producto inactivo: {$p['nombre']}");

      $subtotal += ((float)$p["precio"]) * $cant;
    }

    $iva = round($subtotal * 0.15, 2);
    $total = round($subtotal + $iva, 2);
    $numero = "FAC-" . time();

    // insertar cabecera
    $st = $this->pdo->prepare(
      "INSERT INTO facturas(cliente_id,numero,fecha,subtotal,iva,total,estado)
       VALUES (?,?,?,?,?,?,?)"
    );
    $st->execute([
      $cliente_id,
      $numero,
      $fecha,
      $subtotal,
      $iva,
      $total,
      "EMITIDA"
    ]);

    $factura_id = (int)$this->pdo->lastInsertId();
    if ($factura_id <= 0) throw new Exception("No se pudo crear factura");

    // insertar detalle + descontar stock
    $insDet = $this->pdo->prepare(
      "INSERT INTO detalle_factura(factura_id,producto_id,cantidad,precio_unitario,total)
       VALUES (?,?,?,?,?)"
    );
    $updStock = $this->pdo->prepare("UPDATE productos SET stock = GREATEST(stock-?,0) WHERE id=?");

    foreach ($items as $i) {
      $pid = (int)$i["producto_id"];
      $cant = (int)$i["cantidad"];

      $getProd->execute([$pid]);
      $p = $getProd->fetch();

      $pu = (float)$p["precio"];
      $tl = round($pu * $cant, 2);

      $insDet->execute([$factura_id, $pid, $cant, $pu, $tl]);
      $updStock->execute([$cant, $pid]);
    }

    $this->pdo->commit();

    return [
      "ok"=>true,
      "id"=>$factura_id,
      "numero"=>$numero,
      "subtotal"=>$subtotal,
      "iva"=>$iva,
      "total"=>$total
    ];
  }

  public function findHeader($id) {
    $st = $this->pdo->prepare("
      SELECT f.*, c.nombres, c.cedula, c.email, c.telefono, c.direccion
      FROM facturas f
      JOIN clientes c ON c.id = f.cliente_id
      WHERE f.id = ?
    ");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  public function findItems($factura_id) {
    $st = $this->pdo->prepare("
      SELECT d.*, p.codigo, p.nombre
      FROM detalle_factura d
      JOIN productos p ON p.id = d.producto_id
      WHERE d.factura_id = ?
      ORDER BY d.id ASC
    ");
    $st->execute([(int)$factura_id]);
    return $st->fetchAll();
  }

  public function findWithItems($id) {
    $header = $this->findHeader($id);
    if (!$header) return null;

    $items = $this->findItems($id);

    return [
      "header" => $header,
      "items"  => $items
    ];
  }
}
