<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in da Catequese - ShepherdPath</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <!-- Container Principal do Alpine.js -->
    <div x-data="checkinForm()" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        
        <!-- Header -->
        <div class="bg-blue-600 p-6 text-white text-center">
            <h1 class="text-2xl font-bold">Catequese Paroquial</h1>
            <p class="text-blue-100 mt-1">Check-in de Presença da Santa Missa</p>
        </div>

        <!-- Formulário -->
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('presenca.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Oculto até Etapa/Catequista Serem selecionados -->

                <!-- Horário da Missa -->
                <div>
                    <label for="missa_id" class="block text-sm font-medium text-gray-700 mb-1">Horário da Missa</label>
                    <select id="missa_id" name="missa_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="" disabled selected>Selecione a missa...</option>
                        @foreach($missas as $missa)
                            <option value="{{ $missa->id }}">{{ $missa->descricao }}</option>
                        @endforeach
                    </select>
                    @error('missa_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Etapa da Catequese -->
                <div>
                    <label for="etapa_id" class="block text-sm font-medium text-gray-700 mb-1">Qual é a sua Etapa?</label>
                    <select id="etapa_id" name="etapa_id" x-model="etapaSelecionada" @change="catequistaSelecionado = ''; nome_completo = ''" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="" disabled selected>Selecione a etapa...</option>
                        @foreach($etapas as $etapa)
                            <option value="{{ $etapa->id }}">{{ $etapa->nome }}</option>
                        @endforeach
                    </select>
                    @error('etapa_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Filtro Dinâmico de Catequistas via Alpine.js -->
                <div x-show="etapaSelecionada !== ''" x-transition.duration.300ms x-cloak>
                    <label for="catequista_id" class="block text-sm font-medium text-gray-700 mb-1">Quem são seus(suas) Catequistas?</label>
                    <select id="catequista_id" name="catequista_id" x-model="catequistaSelecionado" @change="nome_completo = ''" required :disabled="etapaSelecionada === ''"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-400">
                        <option value="" disabled selected>Selecione os catequistas...</option>
                        
                        <!-- Interação JavaScript para re-renderizar Options -->
                        <template x-for="catequista in catequistasFiltrados" :key="catequista.id">
                            <option :value="catequista.id" x-text="catequista.nomes"></option>
                        </template>
                    </select>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Mostrando apenas catequistas da etapa selecionada.
                    </p>
                    @error('catequista_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nome do Aluno (Aparece apos Catequista e Busca Dinâmica) -->
                <div x-show="catequistaSelecionado !== ''" x-transition.duration.300ms x-cloak>
                    <label for="nome_completo" class="block text-sm font-medium text-gray-700 mb-1">Qual o seu nome?</label>
                    <input type="text" id="nome_completo" name="nome_completo" x-model="nome_completo" required list="alunos-dinamicos"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Digite pra buscar ou cadastrar..." autocomplete="off">
                    
                    <datalist id="alunos-dinamicos">
                        <template x-for="aluno in alunosFiltrados" :key="aluno.id">
                            <option x-bind:value="aluno.nome_completo"></option>
                        </template>
                    </datalist>
                    <p class="text-xs text-gray-500 mt-2">
                        Seu nome não está na lista? É só digitar o nome completo!
                    </p>
                    @error('nome_completo')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botão de Envio -->
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition-colors duration-300 flex justify-center items-center group">
                    <span>Confirmar Presença</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 p-4 border-t text-center text-xs text-gray-500">
            Powered by ShepherdPath
        </div>
    </div>

    <!-- Script de Inicialização e Lógica Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkinForm', () => ({
                etapaSelecionada: '',
                catequistaSelecionado: '',
                nome_completo: '',
                // Passa a collection inteira de etapas serializada do Blade para JS 
                etapasData: @json($etapas),
                
                get catequistasFiltrados() {
                    if (!this.etapaSelecionada) {
                        this.catequistaSelecionado = '';
                        return [];
                    }
                    
                    // Encontra a etapa completa no array local
                    const etapa = this.etapasData.find(e => e.id == this.etapaSelecionada);
                    return etapa ? etapa.catequistas : [];
                },

                get alunosFiltrados() {
                    if (!this.catequistaSelecionado) return [];

                    const listagemCatequistas = this.catequistasFiltrados;
                    const catequistaInfo = listagemCatequistas.find(c => c.id == this.catequistaSelecionado);

                    return catequistaInfo && catequistaInfo.alunos ? catequistaInfo.alunos : [];
                }
            }))
        })
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
