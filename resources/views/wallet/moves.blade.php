<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Movimientos</h2>
            <a href="{{ route('wallet.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Movimientos recientes -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 col-span-1 md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Listado de Movimientos</h3>
            </div>

            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Tipo</th>
                        <th class="px-4 py-2">Cantidad</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Modificar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($movement->date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $movement->type === 'deposito' ? 'Depósito' : 'Retirada' }}</td>
                            <td class="px-4 py-2">
                                <span class="{{ $movement->type === 'deposito' ? 'text-green-500' : 'text-red-500' }}">
                                    ${{ number_format($movement->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $movement->description ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <a href="#" class="inline-flex items-center px-2 py-1 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">No hay movimientos aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

