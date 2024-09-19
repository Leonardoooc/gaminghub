<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Principal - Development Mode') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <form method="GET" action="{{ route('gamesTest') }}">
            <x-primary-button class="ms-3">
                {{ __('Games List') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('gameSearch') }}" class="space-y-4 text-black-900 dark:text-black-100">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <div class="mt-1">
                    <label for="name" class="text-sm leading-6 font-medium text-black-900 dark:text-black mb-2 mt-3">Nome</label>
                    <input type="text" class="form-input w-full sm:text-sm border-gray-300 rounded-md text-black" name="name" id="name" placeholder="Nome">
                </div>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="submit" id="botao" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-2">Pesquisar</button>
            </div>
        </form>

        <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Lista de Jogos') }}</h3>
            
                @if(session('games') !== null)
                    @php
                        $games = session('games', []);
                    @endphp

                    @if(empty($games))
                        <p>{{ __('Nenhum jogo encontrado.') }}</p>
                    @else
                        <ul class="list-disc list-inside">
                            @foreach($games as $game)
                                <li class="mb-2">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $game['name'] }}
                                            @if($game['coverUrl'])
                                                <a href="{{ route('viewGamePage', $game['id']) }}">
                                                    <img src="{{ $game['coverUrl'] }}" alt="Capa do jogo {{ $game['name'] }}">
                                                </a>
                                            @else
                                                <br>
                                                <a href="{{ route('viewGamePage', $game['id']) }}">
                                                    Sem capa disponível
                                                </a>
                                            @endif
                                            <br><br>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p>{{ __('Clique em Games List') }}</p>
                @endisset
            </div>
        </div>
    </div>
</x-app-layout>