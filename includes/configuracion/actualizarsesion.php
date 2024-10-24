<?php
session_start();
include '../../config/config.php'; // Asegúrate de que este archivo contenga la conexión a la base de datos con PDO

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Obtener el ID del usuario desde la sesión (asegúrate de que el ID esté en la sesión)
    $user_id = $_SESSION['user_id'];

    // Preparar la consulta para actualizar el nombre de usuario y la contraseña sin encriptamiento
    try {
        $sql = "UPDATE usuarios SET username = :usuario, password = :password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Perfil actualizado exitosamente.";
        } else {
            echo "Error al actualizar el perfil.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

