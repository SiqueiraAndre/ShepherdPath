<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expirado - ShepherdPath</title>
    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden text-center p-8">

        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-[#EF3C74]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Check-in Indisponível</h1>
        <p class="text-gray-600 mb-6">Este link não é mais válido ou a lista de presença já foi encerrada pela
            coordenação.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm text-gray-500 text-left border border-gray-100">
            <strong>O que fazer?</strong><br>
            Procure sua catequista para confirmar sua participação ou solicite um novo QR Code caso a missa ainda esteja
            em andamento.
        </div>

    </div>

</body>

</html>