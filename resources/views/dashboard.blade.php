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

        <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Lista de Jogos') }}</h3>

                @isset($games)
                    @if($games->isEmpty())
                        <p>{{ __('Nenhum jogo encontrado.') }}</p>
                    @else
                        <ul class="list-disc list-inside">
                            @foreach($games as $game)
                                <li class="mb-2">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $game->name }}
                                            @if($game->cover)
                                                <a href="{{ route('viewGamePage', $game->id) }}">
                                                    <img src="{{ $game->cover->url }}" alt="Capa do jogo {{ $game->name }}">
                                                </a>
                                            @else
                                                <br>
                                                <a href="{{ route('viewGamePage', $game->id) }}">
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