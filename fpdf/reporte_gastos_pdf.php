<?php

require '../config/config.php';
require('./fpdf.php');

class PDF extends FPDF
{
    public $suma_total_general;
    public $fecha_inicio;
    public $fecha_fin;

    function Header()
    {
        global $suma_total_general, $fecha_inicio, $fecha_fin;

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

        // Mostrar la suma total de gastos en la parte superior derecha
        $this->Cell(180);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 255);
        $this->Cell(85, 10, utf8_decode("Total de Gastos: S/ " . number_format($suma_total_general, 2)), 0, 0, 'R');
        $this->Ln(10);

        // Mostrar el rango de fechas en el encabezado
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100);
        $this->Cell(100, 10, utf8_decode("Desde: " . date('d/m/Y', strtotime($fecha_inicio)) . " Hasta: " . date('d/m/Y', strtotime($fecha_fin))), 0, 1, 'C');
        $this->Ln(7);

        // Título del reporte
        $this->SetTextColor(228, 100, 0);
        $this->Cell(100);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE GASTOS"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Encabezado de la tabla
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(80, 10, utf8_decode('Material'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Precio U.'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Total'), 1, 1, 'C', 1);
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

// Obtener fechas del formulario o valores por defecto
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-t');

// Consulta SQL con filtro por fechas
$sql = 'SELECT g.material, g.fecha_gasto, g.precio, g.cantidad, 
               (g.precio * g.cantidad) AS total 
        FROM gasto g
        WHERE g.fecha_gasto BETWEEN :fecha_inicio AND :fecha_fin';

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':fecha_inicio', $fecha_inicio);
$stmt->bindParam(':fecha_fin', $fecha_fin);
$stmt->execute();

// Calcular la suma total de gastos
$suma_total_general = 0;
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos as $row) {
    $suma_total_general += $row['total'];
}

$pdf = new PDF();
$pdf->fecha_inicio = $fecha_inicio;
$pdf->fecha_fin = $fecha_fin;
$pdf->suma_total_general = $suma_total_general; // Pasamos el valor al objeto PDF
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);
$pdf->AddPage("landscape");

if (count($datos) > 0) {
    foreach ($datos as $row) {
        $pdf->Cell(80, 10, utf8_decode($row['material']), 1);
        $pdf->Cell(30, 10, utf8_decode($row['fecha_gasto']), 1);
        $pdf->Cell(30, 10, utf8_decode('S/ ' . number_format($row['precio'], 2)), 1);
        $pdf->Cell(30, 10, utf8_decode($row['cantidad']), 1);
        $pdf->Cell(40, 10, utf8_decode('S/ ' . number_format($row['total'], 2)), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No se encontraron datos en el rango seleccionado.', 0, 1, 'C');
}

$pdf->Output('I', 'reporte_gastos.pdf');
