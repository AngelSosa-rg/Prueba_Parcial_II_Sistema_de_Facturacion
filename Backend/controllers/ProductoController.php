<?php
require_once __DIR__ . "/../models/Producto.php";

class ProductoController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new Producto();
  }

  public function index() {
    $this->json($this->model->all());
  }

  public function create() {
    $data = $this->body();
    $id = $this->model->create($data);
    $this->json(["ok"=>true, "id"=>$id], 201);
  }

  public function update() {
    $data = $this->body();
    $this->model->update($data);
    $this->json(["ok"=>true]);
  }

  public function delete() {
    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) $this->json(["error"=>"id requerido"], 400);
    $this->model->delete($id);
    $this->json(["ok"=>true]);
  }
}
