<?php
// Conectar a la base de datos usando PDO
// Asegúrate de que la conexión a la base de datos esté correctamente configurada
// $pdo = new PDO('mysql:host=localhost;dbname=tu_base_de_datos', 'usuario', 'contraseña');

// Determinar el tipo de reporte
$reporte = isset($_GET['reporte']) ? $_GET['reporte'] : 'diario';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Inicializar condiciones de consulta
$dateCondition = '';

// Crear condición de fecha según el tipo de reporte
if ($fecha_inicio && $fecha_fin) {
    $dateCondition = "g.fecha_gasto BETWEEN :fecha_inicio AND :fecha_fin";
} elseif ($reporte == 'diario') {
    $dateCondition = "DATE(g.fecha_gasto) = CURDATE()";
} elseif ($reporte == 'semanal') {
    $dateCondition = "YEARWEEK(g.fecha_gasto, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($reporte == 'mensual') {
    $dateCondition = "MONTH(g.fecha_gasto) = MONTH(CURDATE()) AND YEAR(g.fecha_gasto) = YEAR(CURDATE())";
}

// Construir consulta SQL
$query = "
    SELECT 
        g.material, 
        g.precio, 
        g.cantidad, 
        g.fecha_gasto, 
        (g.precio * g.cantidad) AS total
    FROM 
        gasto g
    WHERE 
        1=1
        " . ($dateCondition ? " AND " . $dateCondition : '') . "
";

try {
    $stmt = $pdo->prepare($query);

    // Asociar parámetros si se proporciona un rango de fechas
    if ($fecha_inicio && $fecha_fin) {
        $stmt->bindValue(':fecha_inicio', $fecha_inicio);
        $stmt->bindValue(':fecha_fin', $fecha_fin);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Manejar error de ejecución
        echo "Error al ejecutar la consulta.";
        $gastos = [];
    }
} catch (PDOException $e) {
    // Manejar errores de PDO
    echo "Error en la consulta: " . $e->getMessage();
    $gastos = [];
}

// Inicializar variables para calcular los totales
$totalGastos = 0;
?>

<!-- Mostrar el reporte -->
<h2>Reporte <?php echo ucfirst($reporte); ?></h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Material</th>
            <th>Precio Unitario</th>
            <th>Cantidad</th>
            <th>Fecha</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gastos as $gasto): ?>
            <tr>
                <td><?php echo htmlspecialchars($gasto['material']); ?></td>
                <td><?php echo htmlspecialchars(number_format($gasto['precio'], 2)); ?></td>
                <td><?php echo htmlspecialchars($gasto['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($gasto['fecha_gasto']); ?></td>
                <td><?php echo htmlspecialchars(number_format($gasto['total'], 2)); ?></td>
            </tr>
            <?php
            // Acumulación de totales
            $totalGastos += $gasto['total'];
            ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            <th><?php echo htmlspecialchars(number_format($totalGastos, 2)); ?></th>
        </tr>
    </tfoot>
</table>
