<?php
class Router {
  public static function dispatch() {
    $c = strtolower($_GET["c"] ?? "cliente");
    $a = strtolower($_GET["a"] ?? "index");

    $map = [
      "cliente"  => "ClienteController",
      "producto" => "ProductoController",
      "factura"  => "FacturaController",
      "reporte"  => "ReporteController",
    ];

    if (!isset($map[$c])) {
      http_response_code(404);
      echo "Controller no existe";
      exit;
    }

    $controllerName = $map[$c];
    $file = __DIR__ . "/../controllers/$controllerName.php";

    require_once __DIR__ . "/../core/BaseController.php";
    require_once $file;

    $controller = new $controllerName();
    if (!method_exists($controller, $a)) {
      http_response_code(404);
      echo "Action no existe";
      exit;
    }

    $controller->$a();
  }
}
