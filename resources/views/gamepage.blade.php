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
            <!-- Container principal -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Informações do Jogo -->

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
        </div>
    </div>
</x-app-layout>
