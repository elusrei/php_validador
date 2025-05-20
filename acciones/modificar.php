<?php
session_start();
require_once '../config/db.php';
require_once '../includes/funciones.php';

// Verificar si existe un usuario en sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

// Obtener datos del usuario desde la base de datos
$id = $_SESSION['usuario_id'];
$stmt = $conexion->prepare("SELECT * FROM perfiles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    // Si no se encuentra el usuario, redirigir al índice
    $_SESSION['errores']['general'] = "No se encontró el perfil solicitado.";
    header("Location: ../index.php");
    exit;
}

// Obtener los datos del usuario
$usuario = $resultado->fetch_assoc();

// Convertir string de software a array
$software_array = softwareStringToArray($usuario['softwares']);

// Procesar el formulario de modificación si se envía
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
    
    // Validar nombre
    $error_nombre = validarNombre($nombre);
    if (!empty($error_nombre)) {
        $errores['nombre'] = $error_nombre;
    } else {
        $datos_validos['nombre'] = $nombre;
    }
    
    // Validar contraseña (solo si se proporciona una nueva)
    if (!empty($password)) {
        $error_password = validarPassword($password);
        if (!empty($error_password)) {
            $errores['password'] = $error_password;
        } else {
            // Hashear la contraseña para almacenarla de forma segura
            $datos_validos['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
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
    
    // Si hay errores, mantener en la página de modificación con los mensajes
    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
        // Actualizar los datos del usuario para mostrar los valores enviados
        $usuario['nombre'] = $nombre;
        $usuario['fecha_nacimiento'] = $fecha_nacimiento;
        $usuario['perfil'] = $perfil;
        $software_array = $software;
    } else {
        // Si no hay errores, actualizar en la base de datos
        try {
            // Preparar la consulta SQL según si se cambió la contraseña o no
            if (isset($datos_validos['password'])) {
                $stmt = $conexion->prepare("UPDATE perfiles SET nombre = ?, contraseña = ?, fecha_nacimiento = ?, perfil = ?, softwares = ? WHERE id = ?");
                $stmt->bind_param("sssssi", 
                    $datos_validos['nombre'], 
                    $datos_validos['password'], 
                    $datos_validos['fecha_nacimiento'], 
                    $datos_validos['perfil'], 
                    $datos_validos['softwares'],
                    $id
                );
            } else {
                $stmt = $conexion->prepare("UPDATE perfiles SET nombre = ?, fecha_nacimiento = ?, perfil = ?, softwares = ? WHERE id = ?");
                $stmt->bind_param("ssssi", 
                    $datos_validos['nombre'], 
                    $datos_validos['fecha_nacimiento'], 
                    $datos_validos['perfil'], 
                    $datos_validos['softwares'],
                    $id
                );
            }
            
            if ($stmt->execute()) {
                // Redirigir a la página de perfil con mensaje de éxito
                $_SESSION['exito'] = "¡Perfil actualizado exitosamente!";
                header("Location: ../acciones/perfil.php");
                exit;
            } else {
                // Error al actualizar en la base de datos
                $_SESSION['errores']['db'] = "Error al actualizar los datos: " . $conexion->error;
            }
        } catch (Exception $e) {
            $_SESSION['errores']['db'] = "Error en la base de datos: " . $e->getMessage();
        }
    }
}

// Establecer título para el header
$titulo = "Modificar Perfil";

// Incluir el header
include_once '../includes/header.php';
?>

<!-- Mostrar mensajes de éxito/error generales -->
<?php 
if (isset($_SESSION['errores']['db'])) {
    echo '<div class="p-3 mb-4 bg-red-900/50 text-red-200 rounded-md">';
    echo $_SESSION['errores']['db'];
    echo '</div>';
    unset($_SESSION['errores']['db']);
}
?>

<form action="../acciones/modificar.php" method="POST" class="space-y-4">
    <!-- Nombre -->
    <div>
        <label for="nombre" class="block text-sm font-medium text-blue-300 mb-1">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" 
            value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
            class="w-full px-3 py-2 bg-slate-700 border <?php echo isset($_SESSION['errores']['nombre']) ? 'border-red-500' : 'border-blue-400'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <?php if (isset($_SESSION['errores']['nombre'])): ?>
            <p class="mt-1 text-sm text-red-400"><?php echo $_SESSION['errores']['nombre']; ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Contraseña -->
    <div>
        <label for="password" class="block text-sm font-medium text-blue-300 mb-1">Contraseña (dejar en blanco para mantener la actual)</label>
        <input type="password" id="password" name="password" placeholder="Mínimo 4 caracteres, con número y letra" 
            class="w-full px-3 py-2 bg-slate-700 border <?php echo isset($_SESSION['errores']['password']) ? 'border-red-500' : 'border-blue-400'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <?php if (isset($_SESSION['errores']['password'])): ?>
            <p class="mt-1 text-sm text-red-400"><?php echo $_SESSION['errores']['password']; ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Edad -->
    <div>
        <label for="fecha_nacimiento" class="block text-sm font-medium text-blue-300 mb-1">Fecha de Nacimiento</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
            value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>"
            class="w-full px-3 py-2 bg-slate-700 border <?php echo isset($_SESSION['errores']['fecha_nacimiento']) ? 'border-red-500' : 'border-blue-400'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <?php if (isset($_SESSION['errores']['fecha_nacimiento'])): ?>
            <p class="mt-1 text-sm text-red-400"><?php echo $_SESSION['errores']['fecha_nacimiento']; ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Perfil de artista -->
    <div>
        <label for="perfil" class="block text-sm font-medium text-blue-300 mb-1">¿Con cuál perfil te identificas?</label>
        <select id="perfil" name="perfil" 
            class="w-full px-3 py-2 bg-slate-700 border <?php echo isset($_SESSION['errores']['perfil']) ? 'border-red-500' : 'border-blue-400'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Selecciona una opción</option>
            <option value="disenador" <?php echo ($usuario['perfil'] == 'disenador') ? 'selected' : ''; ?>>Diseñador Gráfico</option>
            <option value="desarrollador" <?php echo ($usuario['perfil'] == 'desarrollador') ? 'selected' : ''; ?>>Desarrollador Web</option>
            <option value="animador" <?php echo ($usuario['perfil'] == 'animador') ? 'selected' : ''; ?>>Animador Digital</option>
            <option value="editor" <?php echo ($usuario['perfil'] == 'editor') ? 'selected' : ''; ?>>Editor de Video</option>
            <option value="fotografo" <?php echo ($usuario['perfil'] == 'fotografo') ? 'selected' : ''; ?>>Fotógrafo</option>
        </select>
        <?php if (isset($_SESSION['errores']['perfil'])): ?>
            <p class="mt-1 text-sm text-red-400"><?php echo $_SESSION['errores']['perfil']; ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Software -->
    <div>
        <label class="block text-sm font-medium text-blue-300 mb-2">Software que conoces:</label>
        <div class="space-y-2">
            <div class="flex items-center">
                <input type="checkbox" id="photoshop" name="software[]" value="Photoshop" 
                    <?php echo (in_array('Photoshop', $software_array)) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="photoshop" class="text-sm">Adobe Photoshop</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="illustrator" name="software[]" value="Illustrator" 
                    <?php echo (in_array('Illustrator', $software_array)) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="illustrator" class="text-sm">Adobe Illustrator</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="premiere" name="software[]" value="Premiere" 
                    <?php echo (in_array('Premiere', $software_array)) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="premiere" class="text-sm">Adobe Premiere</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="aftereffects" name="software[]" value="AfterEffects" 
                    <?php echo (in_array('AfterEffects', $software_array)) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="aftereffects" class="text-sm">Adobe After Effects</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="blender" name="software[]" value="Blender" 
                    <?php echo (in_array('Blender', $software_array)) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="blender" class="text-sm">Blender</label>
            </div>
        </div>
    </div>
    
    <!-- Botones de acción -->
    <div class="mt-6 flex space-x-4">
        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
            Guardar Cambios
        </button>
        <a href="../acciones/perfil.php" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out text-center">
            Cancelar
        </a>
    </div>
</form>

<?php 
// Limpiar errores después de mostrarlos
unset($_SESSION['errores']);
include_once '../includes/footer.php'; 
?>
