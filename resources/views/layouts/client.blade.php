<x-app-layout>
    <x-navbar-client/>
    <main class="pb-[3.5rem]">
        {{ $slot }}
    </main>
    <x-footer-client/>
    @livewire('echo-listen')
</x-app-layout>
