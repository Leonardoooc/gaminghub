<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">Jogos Populares do Momento</h2>

                    <!-- Swiper container -->
                    <div class="swiper-container relative overflow-hidden">
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            @foreach($popularGames as $game)
                            <div class="swiper-slide">
                                <div class="flex-shrink-0 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                                    <a href="{{ route('viewGamePage', $game['id']) }}">
                                        <img src="{{ $game['coverUrl'] }}" alt="Capa do jogo {{ $game['name'] }}" class="w-full h-48 object-cover">
                                        <div class="p-4">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $game['name'] }}</h3>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- Botões de navegação -->
                        <div class="swiper-button-next custom-swiper-button"></div>
                        <div class="swiper-button-prev custom-swiper-button"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-swiper-button {
            background-color: black; /* Fundo preto */
            color: white;            /* Cor do ícone */
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 20px;
            border-radius: 50%;      /* Faz o botão ser um círculo */
            width: 55px;             /* Largura do botão */
            height: 55px;            /* Altura do botão */
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); /* Sombra para destaque */
        }

        .swiper-container {
            z-index: 1;
        }
    </style>

    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 5, // Quantos slides mostrar ao mesmo tempo
            spaceBetween: 20, // Espaçamento entre os slides
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: false, // Desativar o loop infinito
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 5,
                    spaceBetween: 30,
                },
            },
        });
    </script>


</x-app-layout>
