<nav x-data="navigation()" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/logo.png') }}" width="50px" height="50px"/>
                    </a>
                </div>
            </div>

            <!-- Game Search Bar -->
            <div class="relative ms-4" style="width: 40%;">
                <input 
                    type="text" 
                    x-model="search" 
                    @keydown.enter.prevent="goToSearch()"
                    @input.debounce="searchGames()"
                    placeholder="Pesquisar jogos..." 
                    class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm w-full">
                
                <!-- Dropdown de resultados de pesquisa -->
                <div x-show="search !== '' && results.length > 0" class="search-dropdown absolute z-20 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg">
                    <ul>
                        <template x-for="game in results" :key="game.id">
                            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <a :href="'/game/' + game.id" class="block text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center">
                                        <img :src="game.coverUrl ? game.coverUrl : '{{ asset('assets/noimg.jpg') }}'" style="width: 50px; height: 75px; object-fit: cover;" class="mr-1">
                                        <span class="ml-1" x-text="game.name"></span>
                                    </div>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>


            <!-- Settings Dropdown or Login Button -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('viewProfile', ['id' => Auth::user()->id])">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('account.edit')">
                                {{ __('Configurações') }}
                            </x-dropdown-link>
                            
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Sair') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        {{ __('Entrar') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <style>
        .search-dropdown {
            z-index: 20;
        }
    </style>
</nav>

<script>
    function navigation() {
        return {
            search: '',
            results: [],
            searchGames() {
                if (this.search.trim() === '') {
                    this.results = [];
                    return;
                }

                fetch('/search-games?q=' + encodeURIComponent(this.search))
                    .then(response => response.json())
                    .then(data => {
                        this.results = data;
                });
            },
            goToSearch() {
                if (this.search.trim() !== '') {
                    window.location.href = '/search?q=' + encodeURIComponent(this.search);
                }
            }
        }
    }
</script>
