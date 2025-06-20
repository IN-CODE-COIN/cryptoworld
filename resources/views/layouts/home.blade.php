<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-4">
        <h3 class="text-2xl font-bold my-4 text-center">Buscar Criptomoneda</h3>

        <form action="{{ route('crypto.search') }}" method="GET" class="relative flex items-center space-x-4 mb-8">
            <input
                type="text"
                id="search-input"
                name="query"
                placeholder="Nombre o símbolo (Ej: bitcoin, btc)"
                autocomplete="off"
                class="w-full px-4 py-2 border rounded"
            >

            <ul id="suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto z-50 hidden">
                <!-- aquí van las sugerencias -->
            </ul>
        </form>

        @if(isset($crypto))
            <div class="mb-8">
                <h2 class="text-xl font-semibold">Resultado:</h2>
                <p><strong>{{ $crypto['name'] }}</strong> - Precio: ${{ number_format($crypto['price'], 2) }}</p>
            </div>
        @endif

        <h3 class="text-xl font-bold mb-2 text-center">Top 10 Criptomonedas (Capitalización de Mercado)</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mx-auto justify-center">
            @foreach($topCryptos as $coin)
                <div class="h-auto max-w-md bg-white border border-gray-200 rounded-xl shadow-sm p-5 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col items-center space-y-3">
                        <img src="{{ $coin['iconUrl'] }}" alt="{{ $coin['name'] }}" class="h-16 w-16 object-contain" />
                        <h3 class="text-lg font-semibold text-gray-800">{{ $coin['name'] }}</h3>
                        <p class="text-gray-600">💰 Precio: ${{ number_format($coin['price'], 2) }}</p>
                        <p class="text-sm {{ $coin['change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            📈 Cambio 24h: {{ $coin['change'] }}%
                        </p>
                    </div>
                    <div class="flex mt-4 md:mt-6 justify-center">
                        <a href="#" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Añadir a lista</a>
                        <a href="{{ route('crypto.show', $coin['uuid']) }}" class="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Detalles</a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('search-input');
        const suggestions = document.getElementById('suggestions');

        input.addEventListener('input', function () {
            const query = this.value.trim();

            if (query.length < 2) {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
                return;
            }

            fetch(`/crypto/autocomplete?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        suggestions.innerHTML = '';
                        suggestions.style.display = 'none';
                        return;
                    }

                    suggestions.innerHTML = data.map(coin => `
                        <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer flex items-center gap-2" data-uuid="${coin.uuid}">
                            <img src="${coin.iconUrl}" alt="${coin.name}" class="w-5 h-5 object-contain" />
                            <span>${coin.name} (${coin.symbol})</span>
                        </li>
                    `).join('');
                    suggestions.style.display = 'block';

                    // Añadir evento click a cada li para ir a la página detalle
                    suggestions.querySelectorAll('li').forEach(li => {
                        li.addEventListener('click', () => {
                            window.location.href = `/home/${li.getAttribute('data-uuid')}`;
                        });
                    });
                });
        });

        // Opcional: esconder sugerencias si haces click fuera
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });
    });
    </script>
</x-app-layout>

