<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
                Lista de Seguimiento
            </h2>
            <a href="{{ route('home') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium transition">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if(session('success'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 px-4 py-2 rounded">{{ session('success') }}</div>
        @endif

        @if($watchlist->count())
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full table-auto text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Logo</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Símbolo</th>
                        <th class="px-4 py-2">Precio</th>
                        <th class="px-4 py-2">Variación</th>
                        <th class="px-4 py-2">Capitalización Mercado</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($watchlist as $coin)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2"><img src="{{ $coin->icon_url }}" class="w-6 h-6" alt=""></td>
                            <td class="px-4 py-2">{{ $coin->name }}</td>
                            <td class="px-4 py-2">{{ $coin->symbol }}</td>
                            <td class="px-4 py-2">${{ number_format($coin->price, 2) }}</td>
                            <td class="px-4 py-2 {{ $coin->change >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $coin->change }}%</td>
                            <td class="px-4 py-2">${{ number_format($coin->market_cap) }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('watchlist.destroy', $coin) }}" method="POST" onsubmit="return confirm('¿Eliminar esta criptomoneda de tu lista?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p>No tienes criptomonedas en tu lista de seguimiento todavía.</p>
        @endif
    </div>
</x-app-layout>

