<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
                Planes de Suscripción
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10">
        @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="mb-4 text-yellow-600">{{ session('warning') }}</div>
        @endif

        <table class="w-full table-auto border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Características</th>
                    <th class="px-4 py-2">Gratis</th>
                    <th class="px-4 py-2">Pro</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-t">
                    <td class="px-4 py-2">Búsqueda de criptomonedas</td>
                    <td class="px-4 py-2">Sin límite</td>
                    <td class="px-4 py-2">Sin límite</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2">Actualización en tiempo real</td>
                    <td class="px-4 py-2">Sí</td>
                    <td class="px-4 py-2">Sí</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2">Criptos en Watchlist</td>
                    <td class="px-4 py-2">5</td>
                    <td class="px-4 py-2">Sin límite</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2">Seguimiento de cartera</td>
                    <td class="px-4 py-2">No</td>
                    <td class="px-4 py-2">Sí</td>
                </tr>
                <tr class="border-t">
                    <td class="px-4 py-2">Soporte al usuario</td>
                    <td class="px-4 py-2">Mail</td>
                    <td class="px-4 py-2">Telefónico - Mail</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6">
            @auth
                @if ($isPro)
                    <p class="text-green-600 font-semibold">✔ Estás en el plan Pro</p>
                @elseif ($onTrial)
                    <p class="text-yellow-600 font-semibold">
                        🚀 Estás en periodo de prueba. Termina el {{ $user->trial_ends_at->format('d/m/Y h:i A') }}.
                    </p>
                @else
                    <form action="{{ route('start.trial') }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Comenzar prueba gratuita de 7 días
                        </button>
                    </form>
                @endif
            @else
                <p>Inicia sesión para comenzar la prueba gratuita.</p>
            @endauth
        </div>
    </div>
</x-app-layout>
