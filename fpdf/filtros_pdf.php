<?php
require '../config/config.php';
require('./fpdf.php');

// Recibir las fechas del formulario
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Verificar si las fechas llegaron correctamente
if (!$fecha_inicio || !$fecha_fin) {
    die("Error: No se recibieron las fechas.");
}

// Convertir las fechas a formato correcto si es necesario
$fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));
$fecha_fin = date('Y-m-d', strtotime($fecha_fin));

// Consultar datos de la base de datos con filtro de fechas
$sql = "SELECT t.descripcion, t.fecha, t.monto AS costo, w.nombres AS trabajador, 
               mp.nombre AS metodo_pago, 
               COALESCE((SELECT SUM(cantidad * precio) FROM insumo_transacciones it
                        JOIN insumo i ON it.insumo_id = i.id
                        WHERE it.transacciones_id = t.id), 0) AS total_insumos, 
               (t.monto + COALESCE((SELECT SUM(cantidad * precio) FROM insumo_transacciones it
                                   JOIN insumo i ON it.insumo_id = i.id
                                   WHERE it.transacciones_id = t.id), 0)) AS suma_total
        FROM transacciones t
        LEFT JOIN trabajador w ON t.trabajador_id = w.id
        LEFT JOIN metodos_pago mp ON t.metodo_pago_id = mp.id
        WHERE DATE(t.fecha) BETWEEN :fecha_inicio AND :fecha_fin";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
$stmt->bindParam(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
$stmt->execute();

// Iniciar la generación del PDF
$pdf = new FPDF();
$pdf->AddPage("landscape");
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 10, 'REPORTE DE SERVICIOS', 0, 1, 'C');

// Encabezado de la tabla
$pdf->SetFillColor(228, 100, 0);
$pdf->SetTextColor(255);
$pdf->Cell(80, 10, 'Descripcion', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Fecha', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Costo', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Trabajador', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Metodo Pago', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'T. Insumos', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'S. Total', 1, 1, 'C', 1);

// Mostrar datos en la tabla
$pdf->SetTextColor(0);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(80, 10, utf8_decode($row['descripcion']), 1);
    $pdf->Cell(25, 10, utf8_decode($row['fecha']), 1);
    $pdf->Cell(25, 10, utf8_decode($row['costo']), 1);
    $pdf->Cell(50, 10, utf8_decode($row['trabajador']), 1);
    $pdf->Cell(40, 10, utf8_decode($row['metodo_pago']), 1);
    $pdf->Cell(20, 10, utf8_decode($row['total_insumos']), 1);
    $pdf->Cell(20, 10, utf8_decode($row['suma_total']), 1);
    $pdf->Ln();
}

// Descargar el PDF
$pdf->Output('I', 'reporte_servicios.pdf');
?>
