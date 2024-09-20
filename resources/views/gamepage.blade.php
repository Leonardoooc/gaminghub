<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @isset($name)
                <p>{{ $name }}</p>
            @else
                <p>{{ __('Não encontrado') }}</p>
            @endisset
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Informações Principais -->

                    <div class="flex space-x-4">
                        <a href="{{ url()->previous() }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Voltar
                        </a>
                    </div>
                    @isset($name)
                        <div class="flex flex-col md:flex-row">
                            <!-- Imagem do Jogo -->
                            <div class="md:w-1/4 flex justify-center mb-6 md:mb-0">
                                @if($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="Capa do jogo {{ $name }}" class="rounded shadow-md">
                                @else
                                    <p class="text-gray-400 text-center">Sem capa disponível</p>
                                @endif
                            </div>
                            <!-- Detalhes do Jogo -->
                            <div class="md:w-3/4 md:ml-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-300 mb-4">Ranked #11 Popularidade: #2827</p>
                                <p class="mb-4">{{ $summary }}</p>
                            </div>
                        </div>
                    @else
                        <p>{{ __('Não encontrado') }}</p>
                    @endisset
                </div>
            </div>
            @isset($name)
                <!-- Seção de Sinopse e Outros Detalhes -->
                <div class="mt-8">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalhes</h4>
                        <div class="flex items-center">
                            <p class="text-lg text-gray-500 dark:text-gray-300 mt-4">Gêneros:</p>
                            <h3 class="text-gray-400 dark:text-gray-600 ml-2 mt-4"> 
                                @foreach($genres as $genre)
                                    {{ $genre }}@if(!$loop->last), @endif
                                @endforeach
                            </h3>
                        </div>

                        <div class="flex items-center">
                            <p class="text-lg text-gray-500 dark:text-gray-300 mt-4">Desenvolvedores:</p>
                            <h3 class="text-gray-400 dark:text-gray-600 ml-2 mt-4"> 
                                @foreach($developers as $developer)
                                    {{ $developer }}@if(!$loop->last), @endif
                                @endforeach
                            </h3>
                        </div>

                        <div class="flex items-center">
                            <p class="text-lg text-gray-500 dark:text-gray-300 mt-4">Publicadores:</p>
                            <h3 class="text-gray-400 dark:text-gray-600 ml-2 mt-4"> 
                                @foreach($publishers as $publisher)
                                    {{ $publisher }}@if(!$loop->last), @endif
                                @endforeach
                            </h3>
                        </div>

                        <div class="flex items-center">
                            <p class="text-lg text-gray-500 dark:text-gray-300 mt-4">Lançamento:</p>
                            <h3 class="text-gray-400 dark:text-gray-600 ml-2 mt-4"> 
                                {{ \Carbon\Carbon::parse($launchDate)->format('d/m/Y') }}
                            </h3>
                        </div>
                    </div>
                </div>
            @endisset

            @isset($name)
                <!-- Reviews -->
                <div class="mt-8 space-y-12">
                    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">
                        @foreach($reviews as $result)
                        <div class="pb-6 mb-10 border-b-2 border-gray-200 dark:border-gray-600 last:border-none last:mb-0 last:pb-0">
                            <div class="flex items-center mb-6">
                                <!-- Imagem de perfil do usuário -->
                                <img class="w-12 h-12 rounded-full mr-4" src="{{ asset('assets/logo.png') }}">

                                <!-- Nome e descrição do autor -->
                                <div>
                                    <!-- Nome do autor -->
                                    <p class="font-semibold text-lg text-gray-900 dark:text-gray-100" style="margin-left: 5px;">{{ $result['author'] }}</p>
                                    <!-- Data da postagem (opcional) -->
                                </div>
                            </div>

                            <!-- Descrição com "Ver mais" -->
                            <div class="text-sm text-gray-500 dark:text-gray-300 mt-2">
                                <!-- Exibir parte inicial do texto -->
                                <p>
                                    {{ Str::limit($result['description'], 500) }}
                                    <span class="js-visible" style="display: inline;">...</span>
                                    <span class="js-hidden" style="display: none;">{{ substr($result['description'], 500) }}</span>
                                </p>
                                <button class="text-blue-500 hover:underline mt-2 view-more-btn" onclick="toggleText(this)">Ver mais</button>
                            </div>

                            <!-- Linha com os likes e botão de curtir -->
                            <div class="flex justify-between items-center mt-4">
                                <!-- Contador de Likes -->
                                <div class="flex items-center text-gray-500 dark:text-gray-300">
                                    <span class="mr-1">{{ $result['likes'] }} Likes</span>
                                </div>

                                <!-- Botão de Curtir -->
                                <button class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Like
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <script>
                    function toggleText(button) {
                        const visibleSpan = button.previousElementSibling.querySelector('.js-visible');
                        const hiddenSpan = button.previousElementSibling.querySelector('.js-hidden');
                        
                        if (hiddenSpan.style.display === 'none') {
                            hiddenSpan.style.display = 'inline';
                            visibleSpan.style.display = 'none';  // Esconder o "..."
                            button.textContent = 'Ver menos';
                        } else {
                            hiddenSpan.style.display = 'none';
                            visibleSpan.style.display = 'inline';
                            button.textContent = 'Ver mais';
                        }
                    }
                </script>

            @endisset
        </div>
    </div>
</x-app-layout>
