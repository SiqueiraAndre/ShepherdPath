<div class="flex flex-col items-center justify-center space-y-4 p-4" x-data="{
    downloadQrCode() {
        const svg = this.$refs.qrWrapper.querySelector('svg');
        if (!svg) {
            alert('Erro ao processar imagem do QR Code.');
            return;
        }

        const size = 250;
        const scale = 4;
        const canvas = document.createElement('canvas');
        canvas.width = size * scale;
        canvas.height = size * scale;
        const ctx = canvas.getContext('2d');
        
        if (!svg.getAttribute('xmlns')) {
            svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        }
        
        const svgData = new XMLSerializer().serializeToString(svg);
        const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
        const url = URL.createObjectURL(svgBlob);
        
        const img = new Image();
        img.onload = () => {
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            URL.revokeObjectURL(url);
            
            const link = document.createElement('a');
            link.download = 'qrcode-{{ $record->hash }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        };
        img.src = url;
    }
}">
    <div x-ref="qrWrapper" class="p-4 bg-white rounded-xl shadow-sm border border-gray-200">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(url('/?ref=' . $record->hash)) !!}
    </div>

    <x-filament::button
        color="primary"
        @click="downloadQrCode()"
    >
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Baixar Imagem
        </div>
    </x-filament::button>

    <div class="text-center w-full mt-4">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">URL Direta para copiar:</p>
        <div class="flex flex-row items-center gap-2">
            <input type="text" readonly value="{{ url('/?ref=' . $record->hash) }}"
                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2.5 dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                id="url-to-copy-{{ $record->id }}">
            <x-filament::button
                color="gray"
                @click="navigator.clipboard.writeText(document.getElementById('url-to-copy-{{ $record->id }}').value); alert('Link Copiado!')"
            >
                Copiar
            </x-filament::button>
        </div>
    </div>
</div>