<div class="bg-primary-light">
    <div class="min-h-screen flex flex-col pt-12 lg:mt-0 sm:justify-center items-center dark:bg-gray-900">
        <div>
            {{ $logo }}
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-9 py-4 dark:bg-gray-800 overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</div>
