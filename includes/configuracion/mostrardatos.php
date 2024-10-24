<?php
session_start();
require 'config/config.php'; // Asegúrate de incluir tu archivo de conexión

// Suponiendo que tienes el ID de usuario almacenado en la sesión
$userId = $_SESSION['user_id']; // Asegúrate de que el ID de usuario esté en la sesión

// Consulta para obtener los datos del usuario desde la base de datos
$sql = "SELECT nombre,username, email FROM usuarios WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // Obtener los datos del usuario
    $nombre = $result['nombre'];
    $usuario = $result['username'];
    $email = $result['email'];
} else {
    // Manejar el caso en que no se encuentren datos
    echo "No se encontraron datos para el usuario.";
    exit();
}
?>