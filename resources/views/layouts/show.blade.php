<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
                <img src="{{ $coin['iconUrl'] }}" alt="{{ $coin['name'] }}" class="w-10 h-10 md:w-14 md:h-14 object-contain" />
                {{ $coin['name'] }} ({{ $coin['symbol'] }})
            </h2>
            <div>
                <!-- Botón para agregar a la watchlist -->
                @php
                    $isInWatchlist = in_array($coin['uuid'], $watchlistUuids);
                @endphp

                @if ($isInWatchlist)
                    <button disabled class="inline-flex items-center px-4 py-2 text-xs font-medium text-white bg-gray-400 rounded-lg cursor-not-allowed">
                        Ya en lista
                    </button>
                @else
                    <form action="{{ route('watchlist.store') }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $coin['uuid'] }}">
                        <input type="hidden" name="name" value="{{ $coin['name'] }}">
                        <input type="hidden" name="symbol" value="{{ $coin['symbol'] }}">
                        <input type="hidden" name="iconUrl" value="{{ $coin['iconUrl'] }}">
                        <input type="hidden" name="price" value="{{ $coin['price'] }}">
                        <input type="hidden" name="change" value="{{ $coin['change'] }}">
                        <input type="hidden" name="marketCap" value="{{ $coin['marketCap'] }}">
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                            Añadir a lista
                        </button>
                    </form>
                @endif
                <!-- Botón para volver a Home -->
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium transition">
                    ← Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto p-6 bg-white shadow rounded-xl mt-8">
        <!--Mensaje de exito al añadir a la watchlist -->
        @if(session('success'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 text-sm text-yellow-600 bg-yellow-100 px-4 py-2 rounded">
                {{ session('warning') }}
            </div>
        @endif
        {{-- Sección destacada: Precio y cambio --}}
        <div class="text-center mb-8">
            <p class="text-4xl font-bold text-gray-900">
                ${{ number_format($coin['price'], 2) }}
            </p>
            <p class="text-lg {{ $coin['change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $coin['change'] >= 0 ? '▲' : '▼' }} {{ $coin['change'] }}% en las últimas 24h
            </p>
        </div>

        {{-- Info general y descripción --}}
        <div class="mt-6">
            @if($coin['description'])
                <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                    {{ $coin['description'] }}
                </p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-800 dark:text-gray-100 text-sm">
                <p><strong>Market Cap:</strong> ${{ number_format($coin['marketCap']) }}</p>
                <p><strong>Volumen 24h:</strong> ${{ number_format($coin['24hVolume']) }}</p>
                <p><strong>Máximo histórico:</strong> ${{ number_format($coin['allTimeHigh']['price'], 2) }}</p>
                <p><strong>Rank:</strong> #{{ $coin['rank'] }}</p>
                <p><strong>Circulación:</strong> {{ number_format($coin['supply']['circulating']) }}</p>
                <p><strong>Total supply:</strong> {{ number_format($coin['supply']['total']) }}</p>
            </div>

            @if($coin['websiteUrl'])
                <a href="{{ $coin['websiteUrl'] }}" target="_blank" class="inline-block mt-6 text-blue-600 hover:underline font-medium">
                    🌐 Sitio web oficial
                </a>
            @endif
        </div>

        {{-- Enlaces sociales --}}
        @if(isset($coin['links']) && count($coin['links']) > 0)
        <div class="mt-10">
            <h4 class="text-lg font-semibold mb-2">Redes y enlaces</h4>
            <ul class="flex flex-wrap gap-4 text-sm">
                @foreach($coin['links'] as $link)
                    <li>
                        <a href="{{ $link['url'] }}" target="_blank" class="text-blue-500 hover:underline">
                            🔗 {{ ucfirst($link['type']) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</x-app-layout>
