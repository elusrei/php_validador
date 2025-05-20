<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Registro de Artista Multimedia'; ?></title>
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
            <h1 class="text-2xl font-bold text-blue-400 mb-6 text-center"><?php echo $titulo ?? 'Registro de Artista Multimedia'; ?></h1>
            
            <!-- Fecha y hora en Buenos Aires -->
            <div class="mb-6 text-center text-blue-300 text-sm">
                <?php
                    date_default_timezone_set('America/Argentina/Buenos_Aires');
                    echo "Fecha y hora actual: " . date('d/m/Y H:i:s');
                ?>
            </div>
