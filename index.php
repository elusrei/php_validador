<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Multimedia</title>
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
            <h1 class="text-2xl font-bold text-blue-400 mb-6 text-center">Registro de Artista Multimedia</h1>
            
            <!-- Fecha y hora en Buenos Aires -->
            <div class="mb-6 text-center text-blue-300 text-sm">
                <?php
                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    echo "Fecha y hora actual: " . date('d/m/Y H:i:s');
                ?>
            </div>
            
            <form action="verificador.php" method="POST" class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-blue-300 mb-1">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" 
                        class="w-full px-3 py-2 bg-slate-700 border border-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-blue-300 mb-1">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 4 caracteres, con número y letra" 
                        class="w-full px-3 py-2 bg-slate-700 border border-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Edad -->
                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-blue-300 mb-1">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" 
                        class="w-full px-3 py-2 bg-slate-700 border border-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Perfil de artista -->
                <div>
                    <label for="perfil" class="block text-sm font-medium text-blue-300 mb-1">¿Con cuál perfil te identificas?</label>
                    <select id="perfil" name="perfil" 
                        class="w-full px-3 py-2 bg-slate-700 border border-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona una opción</option>
                        <option value="disenador">Diseñador Gráfico</option>
                        <option value="desarrollador">Desarrollador Web</option>
                        <option value="animador">Animador Digital</option>
                        <option value="editor">Editor de Video</option>
                        <option value="fotografo">Fotógrafo</option>
                    </select>
                </div>
                
                <!-- Software -->
                <div>
                    <label class="block text-sm font-medium text-blue-300 mb-2">Software que conoces:</label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="photoshop" name="software[]" value="Photoshop" class="mr-2 text-blue-500">
                            <label for="photoshop" class="text-sm">Adobe Photoshop</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="illustrator" name="software[]" value="Illustrator" class="mr-2 text-blue-500">
                            <label for="illustrator" class="text-sm">Adobe Illustrator</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="premiere" name="software[]" value="Premiere" class="mr-2 text-blue-500">
                            <label for="premiere" class="text-sm">Adobe Premiere</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="aftereffects" name="software[]" value="AfterEffects" class="mr-2 text-blue-500">
                            <label for="aftereffects" class="text-sm">Adobe After Effects</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="blender" name="software[]" value="Blender" class="mr-2 text-blue-500">
                            <label for="blender" class="text-sm">Blender</label>
                        </div>
                    </div>
                </div>
                
                <!-- Botón de envío -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
