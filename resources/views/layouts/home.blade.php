<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Buscar Criptomoneda</h1>

        <form action="{{ route('crypto.search') }}" method="GET" class="flex items-center space-x-4 mb-8">
            <input type="text" name="query" placeholder="Nombre o símbolo (Ej: bitcoin, btc)" class="w-full px-4 py-2 border rounded">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Buscar</button>
        </form>

        @if(isset($crypto))
            <div class="mb-8">
                <h2 class="text-xl font-semibold">Resultado:</h2>
                <p><strong>{{ $crypto['name'] }}</strong> - Precio: ${{ number_format($crypto['price'], 2) }}</p>
            </div>
        @endif

        <h2 class="text-xl font-bold mb-2">Top 10 Criptomonedas (Market Cap)</h2>
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nombre</th>
                    <th class="border px-4 py-2">Precio</th>
                    <th class="border px-4 py-2">Market Cap</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCryptos as $index => $coin)
                <tr>
                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                    <td class="border px-4 py-2">{{ $coin['name'] }}</td>
                    <td class="border px-4 py-2">${{ number_format($coin['price'], 2) }}</td>
                    <td class="border px-4 py-2">${{ number_format($coin['marketCap']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

