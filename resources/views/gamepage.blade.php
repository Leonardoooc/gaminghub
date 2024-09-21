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
                @if(!$userHasReview)
                    <div class="mt-8 space-y-12">
                        <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Escreva seu review</h3>
                            <form id="reviewForm">
                                @csrf
                                <input type="hidden" name="game_id" id="game_id" value="{{ $id }}">
                                <div class="mb-4">
                                    <textarea name="review" id="review" rows="4" class="w-full p-3 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Escreva seu review aqui..."></textarea>
                                </div>
                                <div class="flex justify-between items-center">
                                    <button type="button" id="submitReview" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg shadow-md">
                                        Enviar Review
                                    </button>
                                </div>
                            </form>

                            <p id="message" class="text-red-500 mt-2" style="display:none;"></p>
                        </div>
                    </div>
                @endif
            @endisset

            <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
            <script>
                document.getElementById('submitReview').addEventListener('click', function (e) {
                    e.preventDefault();
                    const reviewText = document.getElementById('review').value;
                    const csrfToken = document.querySelector('input[name="_token"]').value;
                    const gameId = document.getElementById('game_id').value;

                    axios.post('/sendReview', {
                        review: reviewText,
                        gameId: gameId,
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }).then(response => {
                        if (response.data.success) {
                            window.location.reload(false);
                        }
                    }).catch(error => {
                        document.getElementById('message').textContent = 'Erro: ' + error.response.data.error;
                        document.getElementById('message').style.display = 'block';
                    });
                });
            </script>

            @isset($name)
                <!-- Reviews -->
                <div class="mt-8 space-y-12">
                    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6 mt-12" id="reviewList">
                        @foreach($reviews as $result)
                        <div class="pb-6 mb-10 border-b-2 border-gray-200 dark:border-gray-600 last:border-none last:mb-0 last:pb-0">
                            <div class="flex items-center mb-6">
                                <img class="w-12 h-12 rounded-full mr-4" src="{{ asset('assets/logo.png') }}">

                                <div>
                                    <p class="font-semibold text-lg text-gray-900 dark:text-gray-100" style="margin-left: 5px;">{{ $result['author'] }}</p>
                                </div>
                            </div>

                            <div class="text-sm text-gray-500 dark:text-gray-300 mt-2">
                                <p>
                                    {{ Str::limit($result['description'], 500) }}
                                    <span class="js-visible" style="display: inline;">...</span>
                                    <span class="js-hidden" style="display: none;">{{ substr($result['description'], 500) }}</span>
                                </p>
                                <button class="text-blue-500 hover:underline mt-2 view-more-btn" onclick="toggleText(this)">Ver mais</button>
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <div class="flex items-center text-gray-500 dark:text-gray-300">
                                    <span class="mr-1">{{ $result['likes'] }} Likes</span>
                                </div>

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
