<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @isset($name)
                <p>Perfil de {{ $name }}</p>
            @else
                <p>{{ __('Não encontrado') }}</p>
            @endisset
        </h2>
    </x-slot>

    @isset($name)
    <div class="py-12" x-data="{ tab: 'social', search: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tabs de navegação -->
            <div class="flex justify-center mb-4">
                <div class="flex space-x-8">
                    <button 
                        @click="tab = 'social'"
                        :class="tab === 'social' ? 'text-blue-600 dark:text-blue-400 relative' : 'text-gray-500 dark:text-gray-300'"
                        class="text-lg font-semibold pb-2">
                        Social
                        <span :class="tab === 'social' ? 'block w-full h-1 bg-blue-600 dark:bg-blue-400 absolute left-0 bottom-0' : ''"></span>
                    </button>

                    <button 
                        @click="tab = 'games'"
                        :class="tab === 'games' ? 'text-blue-600 dark:text-blue-400 relative' : 'text-gray-500 dark:text-gray-300'"
                        class="text-lg font-semibold pb-2">
                        Jogos
                        <span :class="tab === 'games' ? 'block w-full h-1 bg-blue-600 dark:bg-blue-400 absolute left-0 bottom-0' : ''"></span>
                    </button>
                </div>
            </div>

            <!-- Conteúdo da Tab Social -->
            <div x-show="tab === 'social'">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações Sociais</h3>
                    <p class="mb-4">Bio: {{ $bio ?? 'Sem biografia disponível.' }}</p>
                    <p>Amigos: {{ $friendsCount ?? 0 }}</p>
                </div>
            </div>

            <!-- Conteúdo da Tab Jogos -->
            <div x-show="tab === 'games'">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                    <!-- Campo de pesquisa -->
                    <div class="mb-4 flex justify-center w-2/5">
                        <input type="text" x-model="search" placeholder="Pesquisar jogo..." class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm w-full">
                    </div>

                    <!-- Tabela de jogos -->
                    <table class="min-w-full table-auto mx-auto">
                        <thead>
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100"></th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Jogo</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">Nota do {{ $name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(collect($games)->sortByDesc('score') as $game)
                                <template x-if="search === '' || `{{ $game['name'] }}`.toLowerCase().includes(search.toLowerCase())">
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            <a href="{{ url('game/' . $game['id']) }}">
                                                <img src="{{ $game['coverUrl'] }}" alt="Capa do jogo {{ $game['name'] }}" class="object-cover rounded shadow-md" height="100px" width="100px">
                                            </a>
                                        </td>

                                        <td class="px-6 py-4">
                                            <a href="{{ url('game/' . $game['id']) }}" class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline text-center block">
                                                {{ $game['name'] }}
                                            </a>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-center block">
                                                {{ $game['score'] }}
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    @endisset
</x-app-layout>
