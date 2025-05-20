<?php
// Función para limpiar y validar datos de entrada
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

// Función para validar el nombre
function validarNombre($nombre) {
    if (empty($nombre)) {
        return "Error: El nombre no puede estar vacío.";
    }
    return "";
}

// Función para validar la contraseña
function validarPassword($password) {
    $tiene_letra = preg_match('/[a-zA-Z]/', $password);
    $tiene_numero = preg_match('/[0-9]/', $password);
    $sin_espacios = !preg_match('/\s/', $password);
    $longitud_minima = strlen($password) >= 4;
    
    if (!$tiene_letra || !$tiene_numero || !$sin_espacios || !$longitud_minima) {
        return "Error: La contraseña debe tener al menos 4 caracteres, incluir al menos una letra y un número, y no contener espacios.";
    }
    return "";
}

// Función para validar la edad
function validarEdad($fecha_nacimiento) {
    if (empty($fecha_nacimiento)) {
        return "Error: Debes ingresar tu fecha de nacimiento.";
    }
    
    $hoy = new DateTime();
    $nacimiento = new DateTime($fecha_nacimiento);
    $edad = $hoy->diff($nacimiento)->y;
    
    if ($edad < 18) {
        return "Error: Debes ser mayor de 18 años para registrarte.";
    }
    return "";
}

// Función para validar el perfil
function validarPerfil($perfil) {
    $perfiles_validos = ["disenador", "desarrollador", "animador", "editor", "fotografo"];
    
    if (empty($perfil) || !in_array($perfil, $perfiles_validos)) {
        return "Error: Debes seleccionar un perfil válido.";
    }
    return "";
}

// Función para obtener el nombre del perfil
function obtenerNombrePerfil($perfil) {
    $perfiles = [
        "disenador" => "Diseñador Gráfico",
        "desarrollador" => "Desarrollador Web",
        "animador" => "Animador Digital",
        "editor" => "Editor de Video",
        "fotografo" => "Fotógrafo"
    ];
    
    return $perfiles[$perfil] ?? "Desconocido";
}

// Función para convertir array de software a string para guardar en BD
function softwareArrayToString($software) {
    if (empty($software)) {
        return "";
    }
    return implode(", ", $software);
}

// Función para convertir string de software a array
function softwareStringToArray($software_string) {
    if (empty($software_string)) {
        return [];
    }
    return explode(", ", $software_string);
}
?>
