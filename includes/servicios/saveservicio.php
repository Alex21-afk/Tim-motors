<?php
require '../../config/config.php';

// Obtención de los datos del formulario
$trabajador = $_POST['trabajador'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha'];
$costo = $_POST['costo'];
$metodo_pago = $_POST['metodo_pago'];
$usar_insumos = $_POST['usar_insumos'] === 'si';

// Iniciar la transacción
$pdo->beginTransaction();


    // Guardar el servicio
    $stmt = $pdo->prepare('INSERT INTO	transacciones (trabajador_id, descripcion, fecha, monto, metodo_pago_id) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$trabajador, $descripcion, $fecha, $costo, $metodo_pago]);

    // Obtener el ID del servicio recién insertado
    $servicio_id = $pdo->lastInsertId();

    if ($usar_insumos) {
        // Guardar los insumos
        if (isset($_POST['insumos']) && isset($_POST['cantidades'])) {
            $insumos = $_POST['insumos'];
            $cantidades = $_POST['cantidades'];

            foreach ($insumos as $insumo_id => $nombre) {
                if (isset($cantidades[$insumo_id])) {
                    $cantidad = $cantidades[$insumo_id];

                    // Insertar en la tabla intermedia
                    $stmt = $pdo->prepare('INSERT INTO insumo_transacciones (transacciones_id, insumo_id, cantidad) VALUES (?, ?, ?)');
                    $stmt->execute([$servicio_id, $insumo_id, $cantidad]);
                }
            }
        }
    }

    // Confirmar la transacción
    $pdo->commit();
    
    // Redirigir a la página de servicios
    header('Location: ../../servicios.php');
    exit();

?>
