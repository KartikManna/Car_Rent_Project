<x-app-layout>
    @if(isset($header))
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endif

    {{ $slot }}
</x-app-layout>