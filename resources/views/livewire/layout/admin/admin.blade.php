@props(['title' => config('app.name', 'Laravel')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles


    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('styles')
</head>

<body x-data="{
    darkMode: $persist(localStorage.getItem('darkMode') === 'true' ||
        (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)),
    sidebarOpen: false
}" x-init="$watch('darkMode', value => document.documentElement.classList.toggle('dark', value))" class="min-h-screen dark:bg-gray-900 bg-white"
    :class="{ 'dark': darkMode }" x-cloak>

    @if (Auth::check())
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 sm:hidden" x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false">
        </div>
    @endif

    @if (Auth::check())
        <livewire:layout.admin.includes.navigation />
    @endif

    <!-- Sidebar -->
    @if (Auth::check())
        <aside
            class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" aria-label="Sidebar">
            <livewire:layout.admin.includes.sidebar />
        </aside>
    @endif

    <!-- Main Content -->
    @if (Auth::check())
        <div class="sm:ml-64 dark:text-white dark:bg-gray-900">
            <div class="mt-10 dark:text-white dark:bg-gray-900">
                <div class="flex justify-between items-center dark:text-white dark:bg-gray-900 mt-12 px-4 pt-4">

                    @isset($breadcrumbs)
                        {{ $breadcrumbs }}
                    @endisset

                    @isset($action)
                        {{ $action }}
                    @endisset
                </div>

                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="mt-4 bg-gray-50 rounded-lg dark:border-gray-700 dark:bg-gray-900">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        <main class="bg-gray-50 rounded-lg dark:border-gray-700 dark:bg-gray-900">
            
            {{ $slot }}
        </main>
    @endif


    @livewireScripts
    @wireUiScripts
    @stack('scripts')
    @if (session('swal'))
        <script type="module">
            Swal.fire({!! json_encode(session('swal')) !!});
        </script>
    @endif

    <script>
        document.addEventListener('livewire:navigated', () => {
            initFlowbite();
        });

        Livewire.on('swal', data => {
            Swal.fire(data[0]);
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showAlert', (data) => {
                Swal.fire({
                    icon: data[0].icon,
                    title: data[0].title,
                    text: data[0].text,
                    showConfirmButton: true,
                    timer: 3000
                });
            });
        });
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            /* Echo.channel('notification')
                .listen('NotificationSend', (e) => {
                    console.log('se ha generado una notificación');
                }); */

        });
    </script>

</body>

</html>
