<?php
require '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];

    // Primero, verifica si el nombre ya existe en la base de datos
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM trabajador WHERE nombres = :nombre");
    $checkStmt->bindParam(':nombre', $nombre);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        // Si el nombre ya existe, muestra un mensaje de error con JavaScript
        echo "<script>alert('El nombre ya existe en la base de datos.'); window.location.href = '../../trabajadores.php';</script>";
    } else {
        // Si el nombre no existe, procede con la inserción
        $stmt = $pdo->prepare("INSERT INTO trabajador (nombres) VALUE (:nombre)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();

        // Redirige a la página principal después de la inserción
        header("Location: ../../trabajadores.php");
        exit();
    }
}
?>
