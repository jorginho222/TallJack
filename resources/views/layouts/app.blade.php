<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @livewireStyles

        <style>
            img {
                position: relative;
                height: 100%;
                width: 100%;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        {{-- <x-jet-banner /> --}}

            {{-- @livewire('navigation-menu') --}}

            <!-- Page Heading -->
            {{-- @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif --}}
        <div class="relative h-screen">
            <img src="{{asset('img/blackJack.jpg')}}" class="opacity-75" alt="blackJack">
            
            {{-- @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                    
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif
                    @endauth
                </div>
            @endif  --}}
                    
            <!-- Page Content -->
            <main>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    @yield('content')
                </div>
            </main>
        </div>
        @stack('modals')

        @livewireScripts
    </body>

    <script>
        Livewire.on('showNotification', () => {
            document.getElementById('notification').classList.remove('scale-0')
            document.getElementById('notification').classList.add('scale-100')
        })
                
        Livewire.on('hideNotification', () => {
            document.getElementById('notification').classList.remove('scale-100')
            document.getElementById('notification').classList.add('scale-0')
        })

        // Livewire.on('individualNotification', () => {
        //     document.getElementById('individualNotification').classList.remove('scale-0')
        //     document.getElementById('individualNotification').classList.add('scale-100')
        // })

        // Livewire.on('restart', () => {
        //     document.getElementById('restart').classList.remove('scale-0')
        //     document.getElementById('restart').classList.add('scale-100')
        // })

        Livewire.on('disableBetOptions', () => {
            document.getElementById('betOptions').classList.add('scale-0')
        })

    </script>
</html>
