<?php
require '../../config/config.php'; // Archivo de configuración para la conexión a la base de datos

// Obtener los datos del formulario
$material = $_POST['material'];
$fecha = $_POST['fecha'];
$precio = $_POST['precio'];
$cantidad = $_POST['cantidad'];

// Validación básica
if (empty($material) || empty($fecha) || empty($precio) || empty($cantidad)) {
    // Redirigir a la página del formulario con un mensaje de error
    echo "<script>alert('Error en el registro'); window.location.href = '../../gastos.php';</script>";
    exit();
}

    // Iniciar la transacción
    $pdo->beginTransaction();

    // Insertar el gasto en la base de datos
    $stmt = $pdo->prepare('INSERT INTO gasto (material, fecha_gasto, precio, cantidad) VALUES (?, ?, ?, ?)');
    $stmt->execute([$material, $fecha, $precio, $cantidad]);

    // Confirmar la transacción
    $pdo->commit();

    // Redirigir a la página de gastos con un mensaje de éxito
    header('Location: ../../gastos.php?success=1');
    exit();


?>
