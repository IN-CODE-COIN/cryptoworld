<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
                    Ingresar / Retirar
            </h2>
            <a href="{{ route('wallet.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium transition">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto space-y-8">

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <!-- Formulario de movimiento -->
        <div class="bg-white dark:bg-gray-800 shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Nuevo movimiento</h3>
            <p class="text-sm text-gray-500 mb-2">
                Saldo disponible: <strong>€{{ number_format($balance, 2) }}</strong>
            </p>

            <form method="POST" action="{{ route('wallet.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                        <select name="type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="deposito">Ingreso</option>
                            <option value="retirada">Retirada</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                        <input type="number" name="amount" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Método</label>
                    <select name="method" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="transfer">Transferencia</option>
                        <option value="card">Tarjeta</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción (opcional)</label>
                    <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-xs font-medium">
                    Guardar
                </button>
            </form>
        </div>

    </div>
</x-app-layout>

