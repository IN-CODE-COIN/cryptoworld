<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Nueva Transacción</h2>
            <a href="{{ route('wallet.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium">
                ← Volver
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto">
        <form action="{{ route('wallet.transaction.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded shadow">
            @csrf

            {{-- Aquí pones tu buscador de cryptos que rellene crypto_id y crypto_name --}}

            <div>
                <label for="crypto_id" class="block font-medium text-gray-700">Cripto ID</label>
                <input type="text" name="crypto_id" id="crypto_id" value="{{ old('crypto_id') }}" required class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="crypto_name" class="block font-medium text-gray-700">Nombre Cripto</label>
                <input type="text" name="crypto_name" id="crypto_name" value="{{ old('crypto_name') }}" required class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="type" class="block font-medium text-gray-700">Tipo</label>
                <select name="type" id="type" class="mt-1 block w-full rounded border-gray-300">
                    <option value="buy" {{ old('type') == 'buy' ? 'selected' : '' }}>Compra</option>
                    <option value="sell" {{ old('type') == 'sell' ? 'selected' : '' }}>Venta</option>
                </select>
            </div>

            <div>
                <label for="quantity" class="block font-medium text-gray-700">Cantidad</label>
                <input type="number" step="0.00000001" name="quantity" id="quantity" value="{{ old('quantity') }}" required class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="price_usd" class="block font-medium text-gray-700">Precio por unidad (USD)</label>
                <input type="number" step="0.01" name="price_usd" id="price_usd" value="{{ old('price_usd') }}" required class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="fees" class="block font-medium text-gray-700">Costes (fees)</label>
                <input type="number" step="0.01" name="fees" id="fees" value="{{ old('fees') ?? 0 }}" class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <label for="date" class="block font-medium text-gray-700">Fecha de la transacción</label>
                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded border-gray-300">
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-xs font-medium">Registrar</button>
            </div>
        </form>
    </div>
</x-app-layout>

