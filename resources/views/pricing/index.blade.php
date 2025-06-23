<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight flex items-center gap-4">
                    Planes de Suscripción
            </h2>
            <a href="{{ route('home') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-xs font-medium transition">
                <svg class="w-5 h-4 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                </svg>
                <span>Volver</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-10">
        @if(session('success'))
            <div class="mb-4 text-green-600 bg-green-100 px-4 py-2 rounded">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="mb-4 text-yellow-600 bg-yellow-100 px-4 py-2 rounded">{{ session('warning') }}</div>
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
