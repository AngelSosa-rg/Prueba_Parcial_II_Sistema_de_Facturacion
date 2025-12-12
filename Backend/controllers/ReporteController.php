<?php
require_once __DIR__ . "/../models/Reporte.php";

class ReporteController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new Reporte();
  }

  public function resumen() {
    $this->json($this->model->resumen());
  }

  public function detalle() {
    $cliente_id = (int)($_GET["cliente_id"] ?? 0);
    if ($cliente_id <= 0) $this->json(["error"=>"cliente_id inválido"], 400);
    $this->json($this->model->detalle($cliente_id));
  }
}
