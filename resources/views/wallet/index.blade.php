<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
                    Cartera
            </h2>
            <a href="{{ route('home') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium transition">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto space-y-8">
        <!-- Saldo -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Saldo actual</h3>
            <p class="text-3xl text-green-500 font-bold mt-2">${{ number_format($balance, 2) }}</p>
            <a href="{{ route('wallet.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Ingresar / Retirar
            </a>
        </div>

        <!-- Lista de movimientos -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Movimientos recientes</h3>

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
                    @forelse($movements as $movement)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2">{{ $movement->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ ucfirst($movement->type) }}</td>
                            <td class="px-4 py-2">
                                <span class="{{ $movement->type === 'deposit' ? 'text-green-500' : 'text-red-500' }}">
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
    </div>
</x-app-layout>
