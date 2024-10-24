<?php
require '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Primero, verifica si el nombre del insumo ya existe en la base de datos
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM insumo WHERE nombre = :nombre");
    $checkStmt->bindParam(':nombre', $nombre);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        // Si el nombre del insumo ya existe, muestra un mensaje de error con JavaScript
        echo "<script>alert('El insumo con ese nombre ya existe en la base de datos.'); window.location.href = '../../insumos.php';</script>";
    } else {
        // Si el nombre del insumo no existe, procede con la inserción
        $stmt = $pdo->prepare("INSERT INTO insumo (nombre, precio) VALUES (:nombre, :precio)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':precio', $precio);
        $stmt->execute();

        // Redirige a la página principal después de la inserción
        header("Location: ../../insumos.php");
        exit();
    }
}
?>
