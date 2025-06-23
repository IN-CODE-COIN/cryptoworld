<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Cartera</h2>
            <a href="{{ route('home') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Saldo -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Efectivo</h3>
            <p class="text-3xl text-green-500 font-bold mt-2">${{ number_format(Auth::user()->balance, 2) }}</p>
            <a href="{{ route('wallet.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-xs font-medium">
                Ingresar / Retirar
            </a>
        </div>

        <!-- Añadir transacción -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Nueva transacción</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 p-2">Registra una compra o venta de criptomonedas.</p>
            <a href="{{ route('wallet.transaction.create') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-xs font-medium">
                Añadir Transacción
            </a>
        </div>

        <!-- Movimientos recientes -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 col-span-1 md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Últimos 5 movimientos</h3>
                <a href="{{ route('wallet.moves') }}" class="text-sm text-blue-600 hover:underline">Ver todos</a>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Tipo</th>
                        <th class="px-4 py-2">Cantidad</th>
                        <th class="px-4 py-2">Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements->take(5) as $movement)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $movement->type === 'deposito' ? 'Depósito' : 'Retirada' }}</td>
                            <td class="px-4 py-2">
                                <span class="{{ $movement->type === 'deposito' ? 'text-green-500' : 'text-red-500' }}">
                                    ${{ number_format($movement->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $movement->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">No hay movimientos aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Posiciones activas -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 col-span-1 md:col-span-2">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Posiciones activas</h3>
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Pérdidas / Ganancias</div>
                    <div class="text-lg font-bold {{ $totalProfit >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        ${{ number_format($totalProfit, 2) }}
                    </div>
                    <div class="text-sm {{ $totalChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        {{ number_format($totalChange, 2) }}%
                    </div>
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">Cripto</th>
                        <th class="px-4 py-2">Cantidad</th>
                        <th class="px-4 py-2">Unidades</th>
                        <th class="px-4 py-2">Precio Medio</th>
                        <th class="px-4 py-2">P/G</th>
                        <th class="px-4 py-2">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($positions as $position)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2">{{ $position->symbol }}</td>
                            <td class="px-4 py-2">${{ number_format($position->quantity, 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($position->amount, 4) }}</td>
                            <td class="px-4 py-2">${{ number_format($position->average_price, 2) }}</td>
                            <td class="px-4 py-2 ${ $position->profit >= 0 ? 'text-green-500' : 'text-red-500' }">
                                ${{ number_format($position->profit, 2) }}
                            </td>
                            <td class="px-4 py-2">{{ number_format($position->total_change, 2) }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-gray-500">No hay posiciones activas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
