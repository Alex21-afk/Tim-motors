<?php
require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    $sql = "UPDATE trabajador SET nombres = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $id]);

    header("Location: ../../trabajadores.php");
    exit();
}
?>
