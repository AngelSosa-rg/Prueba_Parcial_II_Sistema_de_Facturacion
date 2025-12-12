<?php

class BaseController {

  protected function json($data, int $status = 200): void {
    http_response_code($status);
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }

  protected function body(): array {
    $raw = file_get_contents("php://input");
    if ($raw) {
      $decoded = json_decode($raw, true);
      if (is_array($decoded)) return $decoded;
    }

    if (!empty($_POST)) return $_POST;

    return [];
  }
}
