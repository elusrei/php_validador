<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';
$usuario = 'root'; // Cambia esto por tu usuario de MySQL
$password = ''; // Cambia esto por tu contraseña de MySQL
$base_datos = 'artistas multimedia'; // Nombre correcto de la base de datos con espacio

// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer charset
$conexion->set_charset("utf8");
?>
