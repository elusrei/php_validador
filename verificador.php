<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Datos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0f172a;
            color: #e2e8f0;
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-slate-800 rounded-lg shadow-lg p-6 border border-blue-500">
            <h1 class="text-2xl font-bold text-blue-400 mb-6 text-center">Resultado de Verificación</h1>
            
            <!-- Fecha y hora en Buenos Aires -->
            <div class="mb-6 text-center text-blue-300 text-sm">
                <?php
                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    echo "Fecha y hora actual: " . date('d/m/Y H:i:s');
                ?>
            </div>
            
            <div class="space-y-4">
                <?php
                // Verificar si se recibieron datos por POST
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                    // 1. Mostrar el valor del nombre con echo
                    $nombre = $_POST["nombre"] ?? "";
                    echo "<div class='p-3 bg-slate-700 rounded-md'>";
                    echo "<p class='font-medium text-blue-300'>Nombre recibido:</p>";
                    echo "<p class='text-white'>" . htmlspecialchars($nombre) . "</p>";
                    echo "</div>";
                    
                    // 2. Validación condicional para el nombre (que no esté vacío)
                    if (empty($nombre)) {
                        echo "<div class='p-3 bg-red-900/50 text-red-200 rounded-md'>";
                        echo "Error: El nombre no puede estar vacío.";
                        echo "</div>";
                    } else {
                        echo "<div class='p-3 bg-green-900/50 text-green-200 rounded-md'>";
                        echo "Nombre válido.";
                        echo "</div>";
                    }
                    
                    // 3. Validación condicional para la contraseña
                    $password = $_POST["password"] ?? "";
                    $tiene_letra = preg_match('/[a-zA-Z]/', $password);
                    $tiene_numero = preg_match('/[0-9]/', $password);
                    $sin_espacios = !preg_match('/\s/', $password);
                    $longitud_minima = strlen($password) >= 4;
                    
                    if ($tiene_letra && $tiene_numero && $sin_espacios && $longitud_minima) {
                        echo "<div class='p-3 bg-green-900/50 text-green-200 rounded-md'>";
                        echo "Contraseña válida.";
                        echo "</div>";
                    } else {
                        echo "<div class='p-3 bg-red-900/50 text-red-200 rounded-md'>";
                        echo "Error: La contraseña debe tener al menos 4 caracteres, incluir al menos una letra y un número, y no contener espacios.";
                        echo "</div>";
                    }
                    
                    // 4. Validación de edad (estructura repetitiva para calcular edad)
                    $fecha_nacimiento = $_POST["fecha_nacimiento"] ?? "";
                    if (!empty($fecha_nacimiento)) {
                        $hoy = new DateTime();
                        $nacimiento = new DateTime($fecha_nacimiento);
                        $edad = $hoy->diff($nacimiento)->y;
                        
                        echo "<div class='p-3 bg-slate-700 rounded-md'>";
                        echo "<p class='font-medium text-blue-300'>Edad calculada:</p>";
                        echo "<p class='text-white'>" . $edad . " años</p>";
                        echo "</div>";
                        
                        if ($edad < 18) {
                            echo "<div class='p-3 bg-red-900/50 text-red-200 rounded-md'>";
                            echo "Error: Debes ser mayor de 18 años para registrarte.";
                            echo "</div>";
                        } else {
                            echo "<div class='p-3 bg-green-900/50 text-green-200 rounded-md'>";
                            echo "Edad válida.";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='p-3 bg-red-900/50 text-red-200 rounded-md'>";
                        echo "Error: Debes ingresar tu fecha de nacimiento.";
                        echo "</div>";
                    }
                    
                    // 5. Estructura repetitiva para el perfil seleccionado
                    $perfil = $_POST["perfil"] ?? "";
                    $perfiles = [
                        "disenador" => "Diseñador Gráfico",
                        "desarrollador" => "Desarrollador Web",
                        "animador" => "Animador Digital",
                        "editor" => "Editor de Video",
                        "fotografo" => "Fotógrafo"
                    ];
                    
                    echo "<div class='p-3 bg-slate-700 rounded-md'>";
                    echo "<p class='font-medium text-blue-300'>Perfil seleccionado:</p>";
                    
                    $perfil_encontrado = false;
                    foreach ($perfiles as $key => $valor) {
                        if ($key == $perfil) {
                            echo "<p class='text-white'>" . $valor . "</p>";
                            $perfil_encontrado = true;
                            break;
                        }
                    }
                    
                    if (!$perfil_encontrado) {
                        echo "<p class='text-red-300'>No se seleccionó ningún perfil</p>";
                    }
                    echo "</div>";
                    
                    // 6. Estructura repetitiva para los software seleccionados
                    $software = $_POST["software"] ?? [];
                    
                    echo "<div class='p-3 bg-slate-700 rounded-md'>";
                    echo "<p class='font-medium text-blue-300'>Software que conoces:</p>";
                    
                    if (count($software) > 0) {
                        echo "<ul class='list-disc pl-5 text-white'>";
                        for ($i = 0; $i < count($software); $i++) {
                            echo "<li>" . htmlspecialchars($software[$i]) . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p class='text-red-300'>No seleccionaste ningún software</p>";
                    }
                    echo "</div>";
                    
                } else {
                    echo "<div class='p-3 bg-red-900/50 text-red-200 rounded-md'>";
                    echo "Error: No se recibieron datos del formulario.";
                    echo "</div>";
                }
                ?>
                
                <div class="mt-6">
                    <a href="index.php" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out text-center">
                        Volver al formulario
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
