<?php
session_start();
require_once '../config/db.php';
require_once '../includes/funciones.php';

// Verificar si se recibieron datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Inicializar arrays para errores y datos válidos
    $errores = [];
    $datos_validos = [];
    
    // Obtener y limpiar datos del formulario
    $nombre = limpiarDato($_POST["nombre"] ?? "");
    $password = $_POST["password"] ?? ""; // No limpiamos la contraseña para no afectar caracteres especiales
    $fecha_nacimiento = limpiarDato($_POST["fecha_nacimiento"] ?? "");
    $perfil = limpiarDato($_POST["perfil"] ?? "");
    $software = $_POST["software"] ?? [];
    
    // Guardar los datos en la sesión para mantenerlos en caso de error
    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'fecha_nacimiento' => $fecha_nacimiento,
        'perfil' => $perfil,
        'software' => $software
    ];
    
    // Validar nombre
    $error_nombre = validarNombre($nombre);
    if (!empty($error_nombre)) {
        $errores['nombre'] = $error_nombre;
    } else {
        $datos_validos['nombre'] = $nombre;
    }
    
    // Validar contraseña
    $error_password = validarPassword($password);
    if (!empty($error_password)) {
        $errores['password'] = $error_password;
    } else {
        // Hashear la contraseña para almacenarla de forma segura
        $datos_validos['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Validar edad
    $error_edad = validarEdad($fecha_nacimiento);
    if (!empty($error_edad)) {
        $errores['fecha_nacimiento'] = $error_edad;
    } else {
        $datos_validos['fecha_nacimiento'] = $fecha_nacimiento;
    }
    
    // Validar perfil
    $error_perfil = validarPerfil($perfil);
    if (!empty($error_perfil)) {
        $errores['perfil'] = $error_perfil;
    } else {
        $datos_validos['perfil'] = $perfil;
    }
    
    // Procesar software (no requiere validación estricta)
    $datos_validos['softwares'] = softwareArrayToString($software);
    
    // Si hay errores, redirigir al formulario con los mensajes
    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
        header("Location: ../index.php");
        exit;
    }
    
    // Si no hay errores, guardar en la base de datos
    try {
        $stmt = $conexion->prepare("INSERT INTO perfiles (nombre, contraseña, fecha_nacimiento, perfil, softwares) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", 
            $datos_validos['nombre'], 
            $datos_validos['password'], 
            $datos_validos['fecha_nacimiento'], 
            $datos_validos['perfil'], 
            $datos_validos['softwares']
        );
        
        if ($stmt->execute()) {
            // Obtener el ID del nuevo registro
            $nuevo_id = $conexion->insert_id;
            
            // Limpiar datos del formulario de la sesión
            unset($_SESSION['form_data']);
            
            // Redirigir a la página de perfil
            $_SESSION['usuario_id'] = $nuevo_id;
            $_SESSION['exito'] = "¡Perfil creado exitosamente!";
            header("Location: ../acciones/perfil.php");
            exit;
        } else {
            // Error al insertar en la base de datos
            $_SESSION['errores']['db'] = "Error al guardar los datos: " . $conexion->error;
            header("Location: ../index.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['errores']['db'] = "Error en la base de datos: " . $e->getMessage();
        header("Location: ../index.php");
        exit;
    }
    
} else {
    // Si no se recibieron datos por POST, redirigir al formulario
    header("Location: ../index.php");
    exit;
}
?>
