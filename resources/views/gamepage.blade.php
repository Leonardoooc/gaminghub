<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Página de Jogo') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @isset($game)
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $game->name }}</h3>

                    @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="Capa do jogo {{ $game->name }}">
                    @else
                        <br>
                        <a href="{{ route('viewGamePage', $game->id) }}">
                            Sem capa disponível
                        </a>
                    @endif
                    <br>
                    Sumário: {{ $game->summary }}
                @else
                    <p>{{ __('Não encontrado') }}</p>
                @endisset
            </div>
        </div>

        <br>
        <a style="margin-right: 10px;" href="{{ url()->previous() }}">
            <x-primary-button>{{ __('Voltar') }}</x-primary-button>
        </a>
    </div>
</x-app-layout>