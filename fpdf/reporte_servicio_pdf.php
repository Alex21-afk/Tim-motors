<?php
require '../config/config.php';
require('./fpdf.php');

class PDF extends FPDF
{
    public $suma_total_general;

    function Header()
    {
        global $suma_total_general;

        $this->Image('tim.jpg', 270, 5, 20);
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(95);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, utf8_decode('TIM MOTORS'), 1, 1, 'C', 0);
        $this->Ln(3);
        $this->SetTextColor(103);

        // Información de la empresa
        $this->Cell(180);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(96, 10, utf8_decode("Ubicación : Av arquitectos mz A lt2 los pinos"), 0, 0);
        $this->Ln(5);
        $this->Cell(180);
        $this->Cell(59, 10, utf8_decode("Teléfono : 980401256"), 0, 0);
        $this->Ln(5);
        $this->Cell(180);
        $this->Cell(85, 10, utf8_decode("Correo : Timmotorsac1@hotmail.com"), 0, 0);
        $this->Ln(5);
        $this->Cell(180);
        $this->Cell(85, 10, utf8_decode("Sucursal : Pachacútec Ventanilla"), 0, 0);
        $this->Ln(5);

        // Suma total general en la parte superior derecha
        $this->Cell(180);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 255);
        $this->Cell(85, 10, utf8_decode("Suma Total General: S/ " . number_format($suma_total_general, 2)), 0, 0, 'R');
        $this->Ln(10);

        // Título del reporte
        $this->SetTextColor(228, 100, 0);
        $this->Cell(100);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE SERVICIOS"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Encabezado de la tabla
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(80, 10, utf8_decode('Descrip.'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('Costo'), 1, 0, 'C', 1);
        $this->Cell(50, 10, utf8_decode('Trabajador'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('M. pago'), 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('T. Insumos'), 1, 0, 'C', 1);
        $this->Cell(20, 10, utf8_decode('S. Total'), 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-15);
        $this->Cell(540, 10, utf8_decode(date('d/m/Y')), 0, 0, 'C');
    }
}

$trabajador = $_GET['trabajador'] ?? '';
$metodo_pago = $_GET['metodo_pago'] ?? '';
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

// Consulta SQL
$sql = 'SELECT t.descripcion, t.fecha, t.monto AS costo, w.nombres AS trabajador, mp.nombre AS metodo_pago,
        COALESCE((SELECT SUM(cantidad * precio) FROM insumo_transacciones it
                 JOIN insumo i ON it.insumo_id = i.id
                 WHERE it.transacciones_id = t.id), 0) AS total_insumos,
        (t.monto + COALESCE((SELECT SUM(cantidad * precio) FROM insumo_transacciones it
                            JOIN insumo i ON it.insumo_id = i.id
                            WHERE it.transacciones_id = t.id), 0)) AS suma_total
        FROM transacciones t
        LEFT JOIN trabajador w ON t.trabajador_id = w.id
        LEFT JOIN metodos_pago mp ON t.metodo_pago_id = mp.id
        WHERE 1';

if ($trabajador) $sql .= ' AND t.trabajador_id = :trabajador';
if ($metodo_pago) $sql .= ' AND t.metodo_pago_id = :metodo_pago';
if ($fecha_inicio && $fecha_fin) $sql .= ' AND DATE(t.fecha) BETWEEN :fecha_inicio AND :fecha_fin';

$stmt = $pdo->prepare($sql);
if ($trabajador) $stmt->bindParam(':trabajador', $trabajador, PDO::PARAM_INT);
if ($metodo_pago) $stmt->bindParam(':metodo_pago', $metodo_pago, PDO::PARAM_INT);
if ($fecha_inicio && $fecha_fin) {
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
}
$stmt->execute();

// Calcular la suma total general
$suma_total_general = 0;
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos as $row) {
    $suma_total_general += $row['suma_total'];
}

$pdf->suma_total_general = $suma_total_general; // Pasamos el valor al objeto PDF
$pdf->AddPage("landscape");

if (count($datos) > 0) {
    foreach ($datos as $row) {
        $pdf->Cell(80, 10, utf8_decode($row['descripcion']), 1);
        $pdf->Cell(25, 10, utf8_decode($row['fecha']), 1);
        $pdf->Cell(25, 10, utf8_decode('S/ ' . number_format($row['costo'], 2)), 1);
        $pdf->Cell(50, 10, utf8_decode($row['trabajador']), 1);
        $pdf->Cell(40, 10, utf8_decode($row['metodo_pago']), 1);
        $pdf->Cell(20, 10, utf8_decode('S/ ' . number_format($row['total_insumos'], 2)), 1);
        $pdf->Cell(20, 10, utf8_decode('S/ ' . number_format($row['suma_total'], 2)), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No se encontraron datos.', 0, 1, 'C');
}

$pdf->Output('I', 'reporte_servicios.pdf');
?>
