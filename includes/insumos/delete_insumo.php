<?php
require '../../config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM insumo WHERE id = ?');
    $stmt->execute([$id]);

    header("Location: ../../insumos.php");
    exit;
}
?>
