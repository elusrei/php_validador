<?php
session_start();
require_once '../config/db.php';

// Verificar si se recibió un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['errores']['general'] = "ID de perfil no especificado.";
    header("Location: listar.php");
    exit;
}

$id = (int)$_GET['id'];

// Eliminar el perfil
try {
    $stmt = $conexion->prepare("DELETE FROM perfiles WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['exito'] = "Perfil eliminado correctamente.";
    } else {
        $_SESSION['errores']['general'] = "Error al eliminar el perfil: " . $conexion->error;
    }
} catch (Exception $e) {
    $_SESSION['errores']['general'] = "Error en la base de datos: " . $e->getMessage();
}

// Redirigir a la lista de perfiles
header("Location: listar.php");
exit;
?>
