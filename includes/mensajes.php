<?php
// Este archivo ya no se usa para mostrar mensajes de error
// Los errores ahora se muestran debajo de cada campo correspondiente
// Solo se mantiene para compatibilidad con código existente

// Mostrar mensajes de éxito si existen
if (isset($_SESSION['exito']) && !empty($_SESSION['exito'])) {
    echo '<div class="p-3 mb-4 bg-green-900/50 text-green-200 rounded-md">';
    echo $_SESSION['exito'];
    echo '</div>';
    // Limpiar mensaje después de mostrarlo
    unset($_SESSION['exito']);
}
?>
