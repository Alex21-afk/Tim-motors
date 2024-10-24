<?php
require '../../config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM trabajador WHERE id = ?');
    $stmt->execute([$id]);

    header("Location: ../../trabajadores.php");
    exit;
}
?>
