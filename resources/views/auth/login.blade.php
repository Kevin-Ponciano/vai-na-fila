@php use App\Models\User;
 $userLogin = User::first()
@endphp
<x-guest-layout>
    @if(config('app.env') === 'local')
        <script>
            $(document).ready(function () {
                $('#email').val('{{$userLogin->email}}')
                $('#password').val('123')
            })
        </script>
    @endif
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo/>
        </x-slot>
        <div class="text-center mb-4 text-[3rem] font-extrabold text-primary">
            Login
        </div>

        <x-validation-errors class="mb-4"/>

        @session('status')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ $value }}
        </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" class="text-primary"/>
                <x-input id="email" class="block mt-1 w-full border-0" type="email" name="email" :value="old('email')"
                         required autofocus autocomplete="username"/>
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" class="text-primary"/>
                <x-input id="password" class="block mt-1 w-full border-0" type="password" name="password" required
                         autocomplete="current-password"/>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember"/>
                    <span class="ms-2 text-sm text-primary dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex justify-center items-center mt-4 mb-4">
                <button
                    class="bg-primary text-[#ffffff] font-bold py-2 w-full rounded rounded-xl hover:bg-primary transition duration-300 ease-in-out">
                    {{ __('Log in') }}
                </button>
            </div>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

        </form>
    </x-authentication-card>
</x-guest-layout>
