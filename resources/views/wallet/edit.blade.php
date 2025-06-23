<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Editar Movimiento</h2>
            <a href="{{ route('wallet.moves') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6 col-span-1 md:col-span-2">

            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Actualizar descripción</h3>

            <form action="{{ route('wallet.moves.update', $movement->id) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <input type="text" name="description" id="description"
                           value="{{ old('description', $movement->description) }}"
                           class="block w-full px-4 py-2 text-gray-800 dark:text-gray-100 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-500">
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('wallet.moves') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">
                        Actualizar
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
