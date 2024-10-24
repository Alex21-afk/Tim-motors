<?php
require '../../config/config.php';

// Obtención de los datos del formulario
$trabajador = $_POST['trabajador'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha'];
$costo = $_POST['costo'];
$metodo_pago = $_POST['metodo_pago'];

// Iniciar la transacción
$pdo->beginTransaction();

try {
    // Guardar el servicio
    $stmt = $pdo->prepare('INSERT INTO transacciones (trabajador_id, descripcion, fecha, monto, metodo_pago_id) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$trabajador, $descripcion, $fecha, $costo, $metodo_pago]);

    // Obtener el ID del servicio recién insertado
    $servicio_id = $pdo->lastInsertId();

    // Verificar si se han enviado insumos
    if (!empty($_POST['insumos_data'])) {
        // Decodificar los insumos enviados en formato JSON
        $insumos = json_decode($_POST['insumos_data'], true);

        // Verificar si la decodificación fue exitosa
        if (is_array($insumos)) {
            foreach ($insumos as $insumo_id => $insumo) {
                $cantidad = $insumo['cantidad'];

                // Insertar en la tabla intermedia
                $stmt = $pdo->prepare('INSERT INTO insumo_transacciones (transacciones_id, insumo_id, cantidad) VALUES (?, ?, ?)');
                $stmt->execute([$servicio_id, $insumo_id, $cantidad]);
            }
        }
    }

    // Confirmar la transacción
    $pdo->commit();

    // Redirigir a la página de servicios con éxito
    header('Location: ../../servicios.php?success=true');
    exit();

} catch (Exception $e) {
    // En caso de error, revertir la transacción
    $pdo->rollBack();

    // Manejo de errores (opcional: redirigir o mostrar mensaje de error)
    header('Location: ../../servicios.php?error=' . urlencode($e->getMessage()));
    exit();
}
