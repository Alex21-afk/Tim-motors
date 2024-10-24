<?php
// Determinar el tipo de reporte
$reporte = isset($_GET['reporte']) ? $_GET['reporte'] : 'diario';
$metodo_pago_id = isset($_GET['metodo_pago']) ? $_GET['metodo_pago'] : '';
$trabajador_id = isset($_GET['trabajador']) ? $_GET['trabajador'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Inicializar condiciones de consulta
$dateCondition = '';
$paymentCondition = $metodo_pago_id ? " AND t.metodo_pago_id = :metodo_pago_id" : '';
$workerCondition = $trabajador_id ? " AND t.trabajador_id = :trabajador_id" : '';

// Crear condición de fecha según el tipo de reporte
if ($fecha_inicio && $fecha_fin) {
    $dateCondition = "t.fecha BETWEEN :fecha_inicio AND :fecha_fin";
} elseif ($reporte == 'diario') {
    $dateCondition = "DATE(t.fecha) = CURDATE()";
} elseif ($reporte == 'semanal') {
    $dateCondition = "YEARWEEK(t.fecha, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($reporte == 'mensual') {
    $dateCondition = "MONTH(t.fecha) = MONTH(CURDATE()) AND YEAR(t.fecha) = YEAR(CURDATE())";
}

// Construir consulta SQL
$query = "
    SELECT 
        t.descripcion, 
        t.fecha, 
        t.monto, 
        w.nombres AS trabajador, 
        p.nombre AS metodo_pago, 
        COALESCE(SUM(it.cantidad * i.precio), 0) AS total_insumos, 
        (t.monto + COALESCE(SUM(it.cantidad * i.precio), 0)) AS suma_total
    FROM 
        transacciones t
    JOIN 
        trabajador w ON t.trabajador_id = w.id
    JOIN 
        metodos_pago p ON t.metodo_pago_id = p.id
    LEFT JOIN 
        insumo_transacciones it ON t.id = it.transacciones_id
    LEFT JOIN 
        insumo i ON it.insumo_id = i.id
    WHERE 
        1=1
        " . ($dateCondition ? " AND " . $dateCondition : '') . "
        $paymentCondition
        $workerCondition
    GROUP BY 
        t.id, t.descripcion, t.fecha, t.monto, w.nombres, p.nombre
";

try {
    $stmt = $pdo->prepare($query);

    if ($metodo_pago_id) {
        $stmt->bindValue(':metodo_pago_id', $metodo_pago_id, PDO::PARAM_INT);
    }

    if ($trabajador_id) {
        $stmt->bindValue(':trabajador_id', $trabajador_id, PDO::PARAM_INT);
    }

    if ($fecha_inicio && $fecha_fin) {
        $stmt->bindValue(':fecha_inicio', $fecha_inicio);
        $stmt->bindValue(':fecha_fin', $fecha_fin);
    }

    $stmt->execute();
    $transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit;
}

// Inicializar variables para calcular los totales
$totalMonto = 0;
$totalInsumos = 0;
$totalSuma = 0;
?>

<h2>Reporte <?php echo ucfirst($reporte); ?></h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Costo</th>
            <th>Trabajador</th>
            <th>Método de Pago</th>
            <th>Total Insumos</th>
            <th>Suma Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transacciones as $transaccion): ?>
            <tr>
                <td><?php echo htmlspecialchars($transaccion['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($transaccion['fecha']); ?></td>
                <td><?php echo htmlspecialchars($transaccion['monto']); ?></td>
                <td><?php echo htmlspecialchars($transaccion['trabajador']); ?></td>
                <td><?php echo htmlspecialchars($transaccion['metodo_pago']); ?></td>
                <td><?php echo htmlspecialchars(number_format($transaccion['total_insumos'], 2)); ?></td>
                <td><?php echo htmlspecialchars(number_format($transaccion['suma_total'], 2)); ?></td>
            </tr>
            <?php
            // Acumulación de totales
            $totalMonto += $transaccion['monto'];
            $totalInsumos += $transaccion['total_insumos'];
            $totalSuma += $transaccion['suma_total'];
            ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <th><?php echo htmlspecialchars(number_format($totalMonto, 2)); ?></th>
            <th></th>
            <th></th>
            <th><?php echo htmlspecialchars(number_format($totalInsumos, 2)); ?></th>
            <th><?php echo htmlspecialchars(number_format($totalSuma, 2)); ?></th>
        </tr>
    </tfoot>
</table>
