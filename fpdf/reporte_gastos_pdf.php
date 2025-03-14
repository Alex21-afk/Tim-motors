<?php
require '../config/config.php'; // Asegúrate de ajustar la ruta según tu estructura de directorios
require('./fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $tableWidth = 190;
        $this->Image('tim.jpg', 270, 5, 20);
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(95);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, utf8_decode('TIM MOTORS'), 1, 1, 'C', 0);
        $this->Ln(3);
        $this->SetTextColor(103);

        // Información adicional
        $this->Cell(180);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(96, 10, utf8_decode("Ubicación : Av arquitectos mz A lt2 los pinos  "), 0, 0);
        $this->Ln(5);
        $this->Cell(180);
        $this->Cell(59, 10, utf8_decode("Teléfono : 980401256"), 0, 0);
        $this->Ln(5);
        $this->Cell(180);
        $this->Cell(85, 10, utf8_decode("Correo : Timmotorsac1@hotmail.com"), 0, 0);
        $this->Ln(5);
        $this->Cell(180);
        $this->Cell(85, 10, utf8_decode("Sucursal : pachacutec ventanilla"), 0, 0);
        $this->Ln(10);

        // Título del reporte
        $this->SetTextColor(228, 100, 0);
        $this->Cell(100);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE GASTOS"), 0, 1, 'C', 0);
        $this->Ln(7);

        // Encabezado de la tabla
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);
        $this->SetX((297 - $tableWidth) / 2);
        $this->Cell(60, 10, utf8_decode('Material'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Precio'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('Fecha Gasto'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('Total'), 1, 1, 'C', 1);
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

// Obtener fechas del formulario
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// Validar que ambas fechas existan
if ($fecha_inicio && $fecha_fin) {
    // Crear instancia de PDF
    $pdf = new PDF();
    $pdf->AddPage("landscape");
    $pdf->AliasNbPages();
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetDrawColor(163, 163, 163);

    // Modificar consulta SQL para filtrar por fecha
    $sql = 'SELECT material, precio, cantidad, fecha_gasto, (precio * cantidad) AS total 
            FROM gasto 
            WHERE fecha_gasto BETWEEN :fecha_inicio AND :fecha_fin';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->execute();

    // Inicializar suma total
    $tableWidth = 60 + 30 + 30 + 40 + 30;
    $suma_total_general = 0;

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdf->SetX((297 - $tableWidth) / 2);
            $pdf->Cell(60, 10, utf8_decode($row['material']), 1);
            $pdf->Cell(30, 10, utf8_decode(number_format($row['precio'], 2)), 1);
            $pdf->Cell(30, 10, utf8_decode($row['cantidad']), 1);
            $pdf->Cell(40, 10, utf8_decode($row['fecha_gasto']), 1);
            $pdf->Cell(30, 10, utf8_decode(number_format($row['total'], 2)), 1);
            $pdf->Ln();
            $suma_total_general += $row['total'];
        }
    } else {
        $pdf->Cell(0, 10, 'No se encontraron datos en el rango de fechas seleccionado.', 0, 1, 'C');
    }

    // Mostrar la suma total general
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(204, 10, utf8_decode('Suma Total General: '), 0, 0, 'R');
    $pdf->Cell(30, 10, utf8_decode(number_format($suma_total_general, 2)), 1, 1, 'C');

    // Generar y descargar el PDF
    $pdf->Output('I', 'reporte_gastos.pdf');

} else {
    // Si no se reciben fechas, mostrar mensaje
    echo "Error: Debes seleccionar un rango de fechas válido.";
}
?>
