<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto text-center relative overflow-x-auto">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-2xl font-bold">Bienvenido, {{ auth()->user()->name }} 👋</h1>
        </div>
    </div>
</x-app-layout>

