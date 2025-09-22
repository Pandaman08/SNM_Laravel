<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código QR Escaneado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 px-4 py-3">
                <h2 class="text-white text-xl font-semibold text-center">Código QR Escaneado</h2>
            </div>
            
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                
                <p class="text-gray-700 mb-2">Su código QR ha sido escaneado correctamente.</p>
                <div class="bg-gray-50 p-3 rounded-md mb-4">
                    <p class="text-sm text-gray-500">Código:</p>
                    <p class="font-mono text-blue-600">{{ $qrCode }}</p>
                </div>
                
                <p class="text-gray-600 mb-6">Por favor, acérquese al personal autorizado para completar el registro de asistencia.</p>
                
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-xs text-gray-500">Sistema de Gestión Académica - {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>