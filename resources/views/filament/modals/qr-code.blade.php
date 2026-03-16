<div class="flex flex-col items-center justify-center space-y-4 p-4">
    <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
        {{ \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(url('/?ref=' . $record->hash)) }}
    </div>
    <div class="text-center w-full mt-4">
        <p class="text-sm text-gray-600 mb-2">URL Direta para copiar:</p>
        <div class="flex flex-row items-center gap-2">
            <input type="text" readonly value="{{ url('/?ref=' . $record->hash) }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" id="url-to-copy-{{ $record->id }}">
            <button onclick="navigator.clipboard.writeText(document.getElementById('url-to-copy-{{ $record->id }}').value); alert('Link Copiado!');" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">Copiar</button>
        </div>
    </div>
</div>
