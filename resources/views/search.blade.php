<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesquisar Jogos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('search') }}" class="flex items-center space-x-4 justify-center m-0.5">
                        <div class="w-full">
                            <input 
                                type="text" 
                                name="q" 
                                id="name" 
                                value="{{ request('q') }}" 
                                class="form-input w-full sm:text-sm border-gray-300 rounded-md text-black"
                                placeholder="Digite o nome do jogo">
                        </div>
                        <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" style="margin-left: 5px;">
                            Pesquisar
                        </button>
                    </form>

                    <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">

                            @if(isset($games) && count($games) > 0)
                                <ul class="list-none">
                                    @foreach($games as $game)
                                        <li class="mb-6">
                                            <div class="grid grid-cols-2 items-center justify-center">
                                                <div class="flex justify-center">
                                                    <a href="{{ route('viewGamePage', $game['id']) }}">
                                                    @if($game['coverUrl'])
                                                        <img src="{{ $game['coverUrl'] }}" alt="Capa do jogo {{ $game['name'] }}" style="width: 150px; height: 200px; object-fit: cover;" class="hover:opacity-90 transition duration-300">
                                                    @else
                                                        <img src="{{ asset('assets/noimg.jpg') }}" style="width: 150px; height: 200px; object-fit: cover;" class="hover:opacity-90 transition duration-300">
                                                    @endif
                                                    </a>
                                                </div>
                                                <div class="flex justify-center">
                                                    <a href="{{ route('viewGamePage', $game['id']) }}" class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $game['name'] }}
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-400">{{ __('Nenhum resultado') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>