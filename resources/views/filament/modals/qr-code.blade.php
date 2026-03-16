<div class="flex flex-col items-center justify-center space-y-4 p-4">
    <div id="qrcode-wrapper-{{ $record->id }}" class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
        {{ \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(url('/?ref=' . $record->hash)) }}
    </div>

    <button type="button" onclick="downloadQrCode('qrcode-wrapper-{{ $record->id }}', 'qrcode-{{ $record->hash }}.png')" class="flex items-center gap-2 text-white bg-pink-600 hover:bg-pink-700 focus:ring-4 focus:outline-none focus:ring-pink-300 font-medium rounded-lg text-sm px-4 py-2 text-center transition-colors shadow-sm">
        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Baixar Imagem
    </button>

    <div class="text-center w-full mt-4">
        <p class="text-sm text-gray-600 mb-2">URL Direta para copiar:</p>
        <div class="flex flex-row items-center gap-2">
            <input type="text" readonly value="{{ url('/?ref=' . $record->hash) }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" id="url-to-copy-{{ $record->id }}">
            <button onclick="navigator.clipboard.writeText(document.getElementById('url-to-copy-{{ $record->id }}').value); alert('Link Copiado!');" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center">Copiar</button>
        </div>
    </div>

    <script>
        if (typeof window.downloadQrCode === 'undefined') {
            window.downloadQrCode = function(wrapperId, filename) {
                const wrapper = document.getElementById(wrapperId);
                const svg = wrapper.querySelector('svg');
                if (!svg) return;

                const canvas = document.createElement('canvas');
                const size = 250;
                const scale = 4; // High resolution standard
                canvas.width = size * scale;
                canvas.height = size * scale;
                const ctx = canvas.getContext('2d');
                
                // Clonar e normalizar viewBox
                const svgClone = svg.cloneNode(true);
                svgClone.setAttribute('width', size);
                svgClone.setAttribute('height', size);
                
                const svgData = new XMLSerializer().serializeToString(svgClone);
                const blob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                const url = URL.createObjectURL(blob);
                
                const img = new Image();
                img.onload = () => {
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    URL.revokeObjectURL(url);
                    
                    const imgURI = canvas.toDataURL('image/png');
                    const a = document.createElement('a');
                    a.download = filename;
                    a.href = imgURI;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                };
                img.src = url;
            }
        }
    </script>
</div>
