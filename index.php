<?php
session_start();

// Establecer título para el header
$titulo = "Registro de Artista Multimedia";

// Incluir el header
include_once 'includes/header.php';

// Recuperar datos del formulario si existen en la sesión
$form_data = $_SESSION['form_data'] ?? [
    'nombre' => '',
    'fecha_nacimiento' => '',
    'perfil' => '',
    'software' => []
];
?>

<!-- Mostrar mensajes de éxito generales -->
<?php 
if (isset($_SESSION['exito'])) {
    echo '<div class="p-3 mb-4 bg-green-900/50 text-green-200 rounded-md">';
    echo $_SESSION['exito'];
    echo '</div>';
    unset($_SESSION['exito']);
}

// Mostrar errores generales (como errores de base de datos)
if (isset($_SESSION['errores']['general']) || isset($_SESSION['errores']['db'])) {
    echo '<div class="p-3 mb-4 bg-red-900/50 text-red-200 rounded-md">';
    echo $_SESSION['errores']['general'] ?? $_SESSION['errores']['db'];
    echo '</div>';
    unset($_SESSION['errores']['general']);
    unset($_SESSION['errores']['db']);
}
?>

<form action="acciones/validar.php" method="POST" class="space-y-4">
    <!-- Nombre -->
    <div>
        <label for="nombre" class="block text-sm font-medium text-blue-300 mb-1">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" 
            value="<?php echo htmlspecialchars($form_data['nombre']); ?>"
            class="w-full px-3 py-2 bg-slate-700 border <?php echo isset($_SESSION['errores']['nombre']) ? 'border-red-500' : 'border-blue-400'; ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <?php if (isset($_SESSION['errores']['nombre'])): ?>
            <p class="mt-1 text-sm text-red-400"><?php echo $_SESSION['errores']['nombre']; ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Contraseña -->
    <div>
        <label for="password" class="block text-sm font-medium text-blue-300 mb-1">Contraseña</label>
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
            value="<?php echo htmlspecialchars($form_data['fecha_nacimiento']); ?>"
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
            <option value="disenador" <?php echo ($form_data['perfil'] == 'disenador') ? 'selected' : ''; ?>>Diseñador Gráfico</option>
            <option value="desarrollador" <?php echo ($form_data['perfil'] == 'desarrollador') ? 'selected' : ''; ?>>Desarrollador Web</option>
            <option value="animador" <?php echo ($form_data['perfil'] == 'animador') ? 'selected' : ''; ?>>Animador Digital</option>
            <option value="editor" <?php echo ($form_data['perfil'] == 'editor') ? 'selected' : ''; ?>>Editor de Video</option>
            <option value="fotografo" <?php echo ($form_data['perfil'] == 'fotografo') ? 'selected' : ''; ?>>Fotógrafo</option>
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
                    <?php echo (in_array('Photoshop', $form_data['software'])) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="photoshop" class="text-sm">Adobe Photoshop</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="illustrator" name="software[]" value="Illustrator" 
                    <?php echo (in_array('Illustrator', $form_data['software'])) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="illustrator" class="text-sm">Adobe Illustrator</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="premiere" name="software[]" value="Premiere" 
                    <?php echo (in_array('Premiere', $form_data['software'])) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="premiere" class="text-sm">Adobe Premiere</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="aftereffects" name="software[]" value="AfterEffects" 
                    <?php echo (in_array('AfterEffects', $form_data['software'])) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="aftereffects" class="text-sm">Adobe After Effects</label>
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="blender" name="software[]" value="Blender" 
                    <?php echo (in_array('Blender', $form_data['software'])) ? 'checked' : ''; ?>
                    class="mr-2 text-blue-500">
                <label for="blender" class="text-sm">Blender</label>
            </div>
        </div>
    </div>
    
    <!-- Botones de acción -->
    <div class="mt-6 flex space-x-4">
        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
            Registrarse
        </button>
        <a href="admin/listar.php" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out text-center">
            Ver Perfiles
        </a>
    </div>
</form>

<?php 
// Limpiar datos del formulario y errores después de mostrarlos
unset($_SESSION['form_data']);
unset($_SESSION['errores']);

// Incluir el footer
include_once 'includes/footer.php'; 
?>
