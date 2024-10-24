<?php
require '../config/config.php';
require('./fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('tim.jpg', 270, 5, 20); // Logo de la empresa
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(95); // Movernos a la derecha
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
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(59, 10, utf8_decode("Teléfono : 980401256"), 0, 0);
        $this->Ln(5);

        $this->Cell(180);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(85, 10, utf8_decode("Correo : Timmotorsac1@hotmail.com"), 0, 0);
        $this->Ln(5);

        $this->Cell(180);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(85, 10, utf8_decode("Sucursal : pachacutec ventanilla"), 0, 0);
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
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(80, 10, utf8_decode('Descrip.'), 1, 0, 'C', 1); // Ampliado
        $this->Cell(25, 10, utf8_decode('Fecha'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('Costo'), 1, 0, 'C', 1); // Reducido
        $this->Cell(50, 10, utf8_decode('Traba.'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('M. pago'), 1, 0, 'C', 1); // Reducido
        $this->Cell(20, 10, utf8_decode('T. Insumos'), 1, 0, 'C', 1); // Reducido
        $this->Cell(20, 10, utf8_decode('S. Total'), 1, 1, 'C', 1); // Reducido
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $hoy = date('d/m/Y');
        $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C');
    }
}

// Obtener parámetros de la URL
$trabajador = isset($_GET['trabajador']) ? $_GET['trabajador'] : '';
$reporte = isset($_GET['reporte']) ? $_GET['reporte'] : '';
$metodo_pago = isset($_GET['metodo_pago']) ? $_GET['metodo_pago'] : '';

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage("landscape"); //El Landspace hace que la pagina cambie vertical a horizontal
$pdf->AliasNbPages();

$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

// Consultar datos de la base de datos
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
if ($reporte === 'diario') $sql .= ' AND DATE(t.fecha) = CURDATE()';
if ($reporte === 'semanal') $sql .= ' AND WEEK(t.fecha) = WEEK(CURDATE())';
if ($reporte === 'mensual') $sql .= ' AND MONTH(t.fecha) = MONTH(CURDATE())';
if ($metodo_pago) $sql .= ' AND t.metodo_pago_id = :metodo_pago';

$stmt = $pdo->prepare($sql);
if ($trabajador) $stmt->bindParam(':trabajador', $trabajador, PDO::PARAM_INT);
if ($metodo_pago) $stmt->bindParam(':metodo_pago', $metodo_pago, PDO::PARAM_INT);
$stmt->execute();

// Inicializar suma total
$suma_total_general = 0;

// Verificar si hay datos
if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pdf->Cell(80, 10, utf8_decode($row['descripcion']), 1);
        $pdf->Cell(25, 10, utf8_decode($row['fecha']), 1);
        $pdf->Cell(25, 10, utf8_decode($row['costo']), 1); 
        $pdf->Cell(50, 10, utf8_decode($row['trabajador']), 1);
        $pdf->Cell(40, 10, utf8_decode($row['metodo_pago']), 1); 
        $pdf->Cell(20, 10, utf8_decode($row['total_insumos']), 1);
        $pdf->Cell(20, 10, utf8_decode($row['suma_total']), 1); 
        $pdf->Ln(); // Salto de línea

        // Acumulación de la suma total
        $suma_total_general += $row['suma_total'];
    }
} else {
    $pdf->Cell(0, 10, 'No se encontraron datos.', 0, 1, 'C');
}

// Mostrar la suma total general
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(250, 10, utf8_decode('Suma Total General: '), 0, 0, 'R');
$pdf->Cell(20, 10, utf8_decode($suma_total_general), 1, 1, 'C'); // Reducido

// Generar y descargar el PDF
$pdf->Output('I', 'reporte_servicios.pdf');
?>
