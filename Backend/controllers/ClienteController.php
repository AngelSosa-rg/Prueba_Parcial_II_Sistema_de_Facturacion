<?php
require_once __DIR__ . "/../models/Cliente.php";

class ClienteController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new Cliente();
  }

  // GET: /public/index.php?c=cliente&a=index
  public function index() {
    $id = $_GET["id"] ?? null;
    if ($id) $this->json($this->model->find($id));
    $this->json($this->model->all());
  }

  // POST: /public/index.php?c=cliente&a=create
  public function create() {
    $data = $this->body();
    $newId = $this->model->create($data);
    $this->json(["ok"=>true, "id"=>$newId], 201);
  }

  // PUT: /public/index.php?c=cliente&a=update
  public function update() {
    $data = $this->body();
    $this->model->update($data);
    $this->json(["ok"=>true]);
  }

  // DELETE: /public/index.php?c=cliente&a=delete&id=1
  public function delete() {
    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) $this->json(["error"=>"id requerido"], 400);
    $this->model->delete($id);
    $this->json(["ok"=>true]);
  }
}
