<?php
session_start();
require_once '../config/db.php';
require_once '../includes/funciones.php';

// Establecer título para el header
$titulo = "Lista de Perfiles";

// Configuración de paginación
$registros_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Obtener el total de registros
$sql_total = "SELECT COUNT(*) as total FROM perfiles";
$resultado_total = $conexion->query($sql_total);
$fila_total = $resultado_total->fetch_assoc();
$total_registros = $fila_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Obtener registros para la página actual
$sql = "SELECT * FROM perfiles ORDER BY id DESC LIMIT ?, ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $inicio, $registros_por_pagina);
$stmt->execute();
$resultado = $stmt->get_result();

// Incluir el header
include_once '../includes/header.php';
?>

<!-- Mostrar mensajes de éxito/error -->
<?php 
if (isset($_SESSION['exito'])) {
    echo '<div class="p-3 mb-4 bg-green-900/50 text-green-200 rounded-md">';
    echo $_SESSION['exito'];
    echo '</div>';
    unset($_SESSION['exito']);
}

if (isset($_SESSION['errores']['general'])) {
    echo '<div class="p-3 mb-4 bg-red-900/50 text-red-200 rounded-md">';
    echo $_SESSION['errores']['general'];
    echo '</div>';
    unset($_SESSION['errores']['general']);
}
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-blue-300">Perfiles Registrados</h2>
        <a href="../index.php" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out text-center">
            Nuevo Perfil
        </a>
    </div>
    
    <?php if ($resultado->num_rows > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-slate-700 rounded-lg overflow-hidden">
                <thead class="bg-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">Nombre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">Fecha Nacimiento</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">Perfil</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">Software</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-blue-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-600">
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-600/50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white"><?php echo $fila['id']; ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white"><?php echo htmlspecialchars($fila['nombre']); ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white"><?php echo htmlspecialchars($fila['fecha_nacimiento']); ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white"><?php echo htmlspecialchars(obtenerNombrePerfil($fila['perfil'])); ?></td>
                            <td class="px-4 py-3 text-sm text-white">
                                <?php 
                                $software_array = softwareStringToArray($fila['softwares']);
                                if (!empty($software_array)) {
                                    echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200">';
                                    echo count($software_array) . ' software';
                                    echo '</span>';
                                } else {
                                    echo '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-200">';
                                    echo 'Ninguno';
                                    echo '</span>';
                                }
                                ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-white">
                                <div class="flex space-x-2">
                                    <a href="../acciones/perfil.php?id=<?php echo $fila['id']; ?>" class="text-blue-400 hover:text-blue-300">
                                        Ver
                                    </a>
                                    <a href="../acciones/modificar.php?id=<?php echo $fila['id']; ?>" class="text-yellow-400 hover:text-yellow-300">
                                        Editar
                                    </a>
                                    <a href="javascript:void(0)" onclick="confirmarEliminar(<?php echo $fila['id']; ?>)" class="text-red-400 hover:text-red-300">
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <div class="flex justify-center mt-6">
                <nav class="inline-flex rounded-md shadow">
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="px-3 py-2 bg-slate-700 text-blue-300 hover:bg-slate-600 rounded-l-md">
                            Anterior
                        </a>
                    <?php else: ?>
                        <span class="px-3 py-2 bg-slate-800 text-slate-500 rounded-l-md">Anterior</span>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <?php if ($i == $pagina_actual): ?>
                            <span class="px-3 py-2 bg-blue-600 text-white">
                                <?php echo $i; ?>
                            </span>
                        <?php else: ?>
                            <a href="?pagina=<?php echo $i; ?>" class="px-3 py-2 bg-slate-700 text-blue-300 hover:bg-slate-600">
                                <?php echo $i; ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="px-3 py-2 bg-slate-700 text-blue-300 hover:bg-slate-600 rounded-r-md">
                            Siguiente
                        </a>
                    <?php else: ?>
                        <span class="px-3 py-2 bg-slate-800 text-slate-500 rounded-r-md">Siguiente</span>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="p-6 bg-slate-700 rounded-lg text-center">
            <p class="text-blue-300">No hay perfiles registrados aún.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Script para confirmar eliminación -->
<script>
function confirmarEliminar(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este perfil?')) {
        window.location.href = 'eliminar.php?id=' + id;
    }
}
</script>

<?php include_once '../includes/footer.php'; ?>
