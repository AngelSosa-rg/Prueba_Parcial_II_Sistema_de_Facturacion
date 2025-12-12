<?php
require_once __DIR__ . "/../models/Factura.php";

class FacturaController extends BaseController {
  private $model;

  public function __construct() {
    $this->model = new Factura();
  }

  public function index() {
    $rows = $this->model->all();
    if (!is_array($rows)) $rows = [];
    $this->json($rows);
  }

  public function create() {
    try {
      $data = $this->body();
      $res = $this->model->create($data);
      $this->json($res, 201);
    } catch (Exception $e) {
      $this->json(["error" => $e->getMessage()], 400);
    }
  }

  public function show() {
    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) $this->json(["error" => "id requerido"], 400);

    $data = $this->model->findWithItems($id);
    if (!$data) $this->json(["error" => "Factura no encontrada"], 404);

    $this->json($data);
  }

  public function pdf() {
    $id = (int)($_GET["id"] ?? 0);
    if ($id <= 0) { http_response_code(400); echo "ID de factura inválido"; exit; }

    $data = $this->model->findWithItems($id);
    if (!$data) { http_response_code(404); echo "Factura no encontrada"; exit; }

    $h = $data["header"];
    $items = $data["items"] ?? [];

    require_once __DIR__ . "/../lib/fpdf/fpdf.php";
    define('FPDF_FONTPATH', __DIR__ . '/../lib/fpdf/font/');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 15);

    $pdf->SetFont('Helvetica','B',16);
    $pdf->Cell(0,10,utf8_decode("SISTEMA DE FACTURACIÓN ELECTRÓNICA"),0,1,'L');

    $pdf->SetFont('Helvetica','',10);
    $pdf->Cell(0,6,utf8_decode("Distribuidora Nuevo Ecuador"),0,1,'L');
    $pdf->Cell(0,6,utf8_decode("RUC: 0999999999001"),0,1,'L');
    $pdf->Cell(0,6,utf8_decode("Dirección: Santo Domingo - Ecuador"),0,1,'L');

    $pdf->Ln(4);

    $pdf->SetFont('Helvetica','B',11);
    $pdf->Cell(0,8,utf8_decode("FACTURA"),0,1,'R');

    $pdf->SetFont('Helvetica','',10);
    $pdf->Cell(100,6,utf8_decode("Factura N°: ".($h["numero"] ?? "")),0,0,'L');
    $pdf->Cell(0,6,utf8_decode("Fecha: ".($h["fecha"] ?? "")),0,1,'R');

    $estado = $h["estado"] ?? "EMITIDA";
    $pdf->Cell(100,6,utf8_decode("Estado: ".$estado),0,1,'L');

    $pdf->Ln(4);

    $pdf->SetFont('Helvetica','B',11);
    $pdf->Cell(0,8,utf8_decode("DATOS DEL CLIENTE"),0,1,'L');

    $pdf->SetFont('Helvetica','',10);
    $pdf->Cell(0,6,utf8_decode("Nombre: ".($h["nombres"] ?? "")),0,1,'L');
    if (!empty($h["cedula"]))   $pdf->Cell(0,6,utf8_decode("Cédula: ".$h["cedula"]),0,1,'L');
    if (!empty($h["email"]))    $pdf->Cell(0,6,utf8_decode("Email: ".$h["email"]),0,1,'L');
    if (!empty($h["telefono"])) $pdf->Cell(0,6,utf8_decode("Teléfono: ".$h["telefono"]),0,1,'L');
    if (!empty($h["direccion"]))$pdf->Cell(0,6,utf8_decode("Dirección: ".$h["direccion"]),0,1,'L');

    $pdf->Ln(5);

    $pdf->SetFont('Helvetica','B',10);
    $pdf->SetFillColor(230,230,230);
    $pdf->Cell(80,8,utf8_decode("Producto"),1,0,'L',true);
    $pdf->Cell(20,8,"Cant.",1,0,'C',true);
    $pdf->Cell(40,8,"P. Unit",1,0,'R',true);
    $pdf->Cell(40,8,"Total",1,1,'R',true);

    $pdf->SetFont('Helvetica','',10);

    foreach ($items as $it) {
      $nombre = trim(($it["codigo"] ?? "") . " " . ($it["nombre"] ?? ""));
      $pdf->Cell(80,8,utf8_decode(substr($nombre,0,40)),1,0,'L');
      $pdf->Cell(20,8,(string)($it["cantidad"] ?? 0),1,0,'C');
      $pdf->Cell(40,8,number_format((float)($it["precio_unitario"] ?? 0),2),1,0,'R');
      $pdf->Cell(40,8,number_format((float)($it["total"] ?? 0),2),1,1,'R');
    }

    $pdf->Ln(3);
    $pdf->SetFont('Helvetica','B',10);

    $pdf->Cell(140,8,"Subtotal",0,0,'R');
    $pdf->Cell(40,8,number_format((float)($h["subtotal"] ?? 0),2),1,1,'R');

    $pdf->Cell(140,8,"IVA 15%",0,0,'R');
    $pdf->Cell(40,8,number_format((float)($h["iva"] ?? 0),2),1,1,'R');

    $pdf->SetFont('Helvetica','B',12);
    $pdf->Cell(140,10,"TOTAL",0,0,'R');
    $pdf->Cell(40,10,number_format((float)($h["total"] ?? 0),2),1,1,'R');

    $pdf->Ln(10);
    $pdf->SetFont('Helvetica','I',9);
    $pdf->Cell(0,6,utf8_decode("Documento generado electrónicamente."),0,1,'C');
    $pdf->Cell(0,6,utf8_decode("Gracias por su compra."),0,1,'C');

    $filename = "Factura_" . (($h["numero"] ?? $id)) . ".pdf";

    header("Content-Type: application/pdf");
    header('Content-Disposition: attachment; filename="'.$filename.'"');

    $pdf->Output("D", $filename);
    exit;
  }
}
