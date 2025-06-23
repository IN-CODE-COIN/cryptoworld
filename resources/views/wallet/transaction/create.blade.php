<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Nueva Transacción</h2>
            <a href="{{ route('wallet.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium">
                <svg class="w-5 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>
                <span>Volver</span>
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto">
        <form action="{{ route('crypto.search') }}" method="GET" class="relative flex items-center space-x-4 mb-8">
            <input
                type="text"
                id="search-input-transaction"
                name="query"
                placeholder="Nombre o símbolo (Ej: bitcoin, btc)"
                autocomplete="off"
                class="w-full px-4 py-2 border rounded"
            >

            <ul id="suggestions-transaction" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto z-50 hidden">
                <!-- aquí van las sugerencias -->
            </ul>
        </form>
        <form action="{{ route('wallet.transaction.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow">
            @csrf

            <div>
                <label for="crypto_id" class="block font-medium text-gray-700">Cripto ID</label>
                <input type="text" name="crypto_id" id="crypto_id" value="{{ old('crypto_id') }}" required class="mt-1 block w-full rounded border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed" readonly>
            </div>

            <div>
                <label for="crypto_name" class="block font-medium text-gray-700">Nombre</label>
                <input type="text" name="crypto_name" id="crypto_name" value="{{ old('crypto_name') }}" required class="mt-1 block w-full rounded border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed" readonly>
            </div>

            <div>
                <label for="type" class="block font-medium text-gray-700">Tipo</label>
                <select name="type" id="type" class="mt-1 block w-full rounded border-gray-300">
                    <option value="buy" {{ old('type') == 'buy' ? 'selected' : '' }}>Compra</option>
                    <option value="sell" {{ old('type') == 'sell' ? 'selected' : '' }}>Venta</option>
                </select>
            </div>

            <div>
                <label for="date" class="block font-medium text-gray-700">Fecha de la transacción</label>
                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="amount_usd" class="block font-medium text-gray-700">Inversión (USD)</label>
                <input type="number" step="0.01" name="amount_usd" id="amount_usd" value="{{ old('amount_usd') }}" class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="price_usd" class="block font-medium text-gray-700">Precio por unidad (USD)</label>
                <input type="number" step="0.01" name="price_usd" id="price_usd"
                    value="{{ old('price_usd') }}"
                    required readonly
                    class="mt-1 block w-full rounded border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
            </div>

            <div>
                <label for="quantity" class="block font-medium text-gray-700">Unidades cryptomoneda</label>
                <input type="number" step="0.00000001" name="quantity" id="quantity" value="{{ old('quantity') }}" required class="mt-1 block w-full rounded border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
            </div>

            <div>
                <label for="fees" class="block font-medium text-gray-700">Costes ($)</label>
                <input type="number" step="0.01" name="fees" id="fees" value="{{ old('fees') ?? 0 }}" class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-xs font-medium">Registrar</button>
            </div>
        </form>
    </div>
</x-app-layout>

