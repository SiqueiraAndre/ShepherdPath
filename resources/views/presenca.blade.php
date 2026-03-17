<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in da Catequese - ShepherdPath</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <!-- Container Principal do Alpine.js -->
    <div x-data="checkinForm()" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

        <!-- Header -->
        <div class="p-6 text-white text-center flex flex-col items-center" style="background-color: #EF3C74;">
            <!-- Logo da Paróquia -->
            <img src="{{ asset('images/logo.png') }}" alt="Paróquia Nossa Senhora Menina"
                class="w-32 h-32 object-contain mb-4 rounded-full shadow-md border-4 border-white bg-white">
            <h1 class="text-2xl font-bold">Catequese Paroquial</h1>
            <p class="text-[white]/80 mt-1">Check-in de Presença da Santa Missa</p>
        </div>

        <!-- Formulário -->
        <div class="p-6">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 30000)"
                x-transition:leave="transition ease-in duration-700" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="bg-pink-100 border border-pink-400 text-pink-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
            @endif

            <form action="{{ route('presenca.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Oculto até Etapa/Catequista Serem selecionados -->

                <!-- Horário da Missa -->
                <div>
                    <label for="missa_id" class="block text-sm font-medium text-gray-700 mb-1">Horário da Missa</label>
                    <select id="missa_id" name="missa_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3C74] focus:border-[#EF3C74] bg-white">
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
                    <label for="etapa_id" class="block text-sm font-medium text-gray-700 mb-1">Qual é a sua
                        Etapa?</label>
                    <select id="etapa_id" name="etapa_id" x-model="etapaSelecionada"
                        @change="catequistaSelecionado = ''; nome_completo = ''" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3C74] focus:border-[#EF3C74] bg-white">
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
                    <label for="catequista_id" class="block text-sm font-medium text-gray-700 mb-1">Quem são seus(suas)
                        Catequistas?</label>
                    <select id="catequista_id" name="catequista_id" x-model="catequistaSelecionado"
                        @change="nome_completo = ''" required :disabled="etapaSelecionada === ''"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-400">
                        <option value="" disabled selected>Selecione os catequistas...</option>

                        <!-- Interação JavaScript para re-renderizar Options -->
                        <template x-for="catequista in catequistasFiltrados" :key="catequista.id">
                            <option :value="catequista.id" x-text="catequista.nomes"></option>
                        </template>
                    </select>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-[#EF3C74]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mostrando apenas catequistas da etapa selecionada.
                    </p>
                    @error('catequista_id')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nome do Aluno (Aparece apos Catequista e Busca Dinâmica) -->
                <div x-show="catequistaSelecionado !== ''" x-transition.duration.300ms x-cloak class="relative"
                    @click.away="mostrarListaAlunos = false">
                    <label for="nome_completo" class="block text-sm font-medium text-gray-700 mb-1">Qual o seu
                        nome?</label>
                    <input type="text" id="nome_completo" name="nome_completo" x-model="nome_completo" required
                        @focus="mostrarListaAlunos = true" @input="mostrarListaAlunos = true"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EF3C74] focus:border-[#EF3C74] transition-colors"
                        placeholder="Digite pra buscar ou cadastrar..." autocomplete="off">

                    <!-- Dropdown Customizado Tailwind -->
                    <div x-show="mostrarListaAlunos && alunosFiltradosPorBusca.length > 0" x-transition x-cloak
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl max-h-48 overflow-y-auto">
                        <ul class="py-1">
                            <template x-for="aluno in alunosFiltradosPorBusca" :key="aluno.id">
                                <li @click="nome_completo = aluno.nome_completo; mostrarListaAlunos = false"
                                    class="px-4 py-2 hover:bg-[#EF3C74]/10 cursor-pointer text-gray-700 transition-colors"
                                    x-text="aluno.nome_completo"></li>
                            </template>
                        </ul>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Seu nome não está na lista? É só digitar o nome completo!
                    </p>
                    @error('nome_completo')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botão de Envio -->
                <button type="submit" style="background-color: #EF3C74;"
                    class="w-full hover:brightness-110 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition-all duration-300 flex justify-center items-center group">
                    <span>Confirmar Presença</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 p-4 border-t text-center text-xs text-gray-500">
            Powered by <a href="https://github.com/SiqueiraAndre/ShepherdPath" target="_blank"
                class="hover:text-[#EF3C74] transition-colors font-medium">ShepherdPath</a>
        </div>
    </div>

    <!-- Script de Inicialização e Lógica Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkinForm', () => ({
                etapaSelecionada: '',
                catequistaSelecionado: '',
                nome_completo: '',
                mostrarListaAlunos: false,
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
                },

                get alunosFiltradosPorBusca() {
                    const termo = this.nome_completo.toLowerCase();
                    return this.alunosFiltrados.filter(a => a.nome_completo.toLowerCase().includes(termo));
                }
            }))
        })
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>