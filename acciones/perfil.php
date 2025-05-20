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

// Establecer título para el header
$titulo = "Perfil de " . $usuario['nombre'];

// Incluir el header
include_once '../includes/header.php';
?>

<!-- Mostrar mensajes de éxito/error -->
<?php include_once '../includes/mensajes.php'; ?>

<div class="space-y-4">
    <div class="p-3 bg-slate-700 rounded-md">
        <p class="font-medium text-blue-300">Nombre:</p>
        <p class="text-white"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
    </div>
    
    <div class="p-3 bg-slate-700 rounded-md">
        <p class="font-medium text-blue-300">Fecha de Nacimiento:</p>
        <p class="text-white"><?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?></p>
    </div>
    
    <div class="p-3 bg-slate-700 rounded-md">
        <p class="font-medium text-blue-300">Perfil:</p>
        <p class="text-white"><?php echo htmlspecialchars(obtenerNombrePerfil($usuario['perfil'])); ?></p>
    </div>
    
    <div class="p-3 bg-slate-700 rounded-md">
        <p class="font-medium text-blue-300">Software que conoces:</p>
        <?php if (!empty($software_array)): ?>
            <ul class="list-disc pl-5 text-white">
                <?php foreach ($software_array as $software): ?>
                    <li><?php echo htmlspecialchars($software); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-red-300">No seleccionaste ningún software</p>
        <?php endif; ?>
    </div>
    
    <div class="mt-6 flex space-x-4">
        <a href="../acciones/modificar.php" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out text-center">
            Modificar Perfil
        </a>
        <a href="../index.php" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out text-center">
            Volver al Inicio
        </a>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
