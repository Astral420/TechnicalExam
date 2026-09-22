<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Book & Author Management' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=satoshi:400,600" rel="stylesheet" />

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5EDD6] text-[#3D3428] font-sans antialiased min-h-screen">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Mobile Header Bar (<1024px) -->
        <header class="lg:hidden bg-[#3D3428] text-[#E8DFC6] px-4 py-3.5 flex items-center justify-between border-b border-[#4d4233] sticky top-0 z-40">
            <a href="{{ route('books.index') }}" class="flex items-center gap-2.5 font-semibold text-[#F5EDD6] text-[15px]">
                <x-bi-book class="w-5 h-5 text-[#C9A84C]" />
                <span>Book & Author</span>
            </a>
            <button type="button" id="mobile-menu-button" class="p-2 text-[#E8DFC6] hover:text-[#F5EDD6] focus:outline-none" aria-label="Toggle navigation">
                <x-bi-list class="w-6 h-6" />
            </button>
        </header>

        <!-- Sidebar Navigation (256px wide, fixed dark warm-brown spine) -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#3D3428] text-[#E8DFC6] flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto transition-transform duration-200 ease-in-out">
            <!-- App Spine Header -->
            <div class="px-5 py-6 border-b border-[#4d4233] flex items-center justify-between">
                <a href="{{ route('books.index') }}" class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded bg-[#C9A84C]/20 border border-[#C9A84C]/40 flex items-center justify-center text-[#C9A84C]">
                        <x-bi-book class="w-5 h-5" />
                    </span>
                    <div>
                        <div class="text-[14px] font-semibold text-[#F5EDD6] leading-snug">Book & Author</div>
                        <div class="text-[11px] text-[#E8DFC6]/60 uppercase tracking-wider">Ledger Management</div>
                    </div>
                </a>
                <button type="button" id="close-sidebar-button" class="lg:hidden text-[#E8DFC6] hover:text-white p-1">
                    <x-bi-x-lg class="w-5 h-5" />
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 py-4 space-y-1">
                <a href="{{ route('books.index') }}"
                   class="flex items-center gap-3 px-5 py-3 text-[14px] transition-colors {{ request()->routeIs('books.*') ? 'border-l-[3px] border-[#C9A84C] text-[#F5EDD6] bg-[#493e30] font-semibold' : 'text-[#E8DFC6] hover:text-[#F5EDD6] hover:bg-[#453b2e]' }}">
                    <x-bi-book class="w-5 h-5 {{ request()->routeIs('books.*') ? 'text-[#C9A84C]' : 'text-[#E8DFC6]/70' }}" />
                    <span>Books</span>
                </a>

                <a href="{{ route('authors.index') }}"
                   class="flex items-center gap-3 px-5 py-3 text-[14px] transition-colors {{ request()->routeIs('authors.*') ? 'border-l-[3px] border-[#C9A84C] text-[#F5EDD6] bg-[#493e30] font-semibold' : 'text-[#E8DFC6] hover:text-[#F5EDD6] hover:bg-[#453b2e]' }}">
                    <x-bi-person class="w-5 h-5 {{ request()->routeIs('authors.*') ? 'text-[#C9A84C]' : 'text-[#E8DFC6]/70' }}" />
                    <span>Authors</span>
                </a>
            </nav>
        </aside>

        <!-- Backdrop overlay for mobile sidebar -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-[#3D3428]/60 z-40 hidden lg:hidden"></div>

        <!-- Main Content Surface (Cream ground #F5EDD6) -->
        <main class="flex-1 min-w-0 p-6 sm:p-8 lg:p-10 max-w-[1200px]">
            <!-- Dynamic & Session Flash Messages Area -->
            <div id="flash-container" class="mb-6 space-y-3">
                @if (session('success'))
                    <div class="flash-alert bg-[rgba(90,125,76,0.12)] text-[#5A7D4C] border border-[#5A7D4C]/30 rounded-md py-3 px-4 flex items-center justify-between text-[14px]">
                        <div class="flex items-center gap-2.5">
                            <x-bi-check-circle class="w-5 h-5 flex-shrink-0" />
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.closest('.flash-alert').remove()" class="text-[#5A7D4C] hover:opacity-75 p-1">
                            <x-bi-x class="w-4 h-4" />
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flash-alert bg-[rgba(180,77,59,0.12)] text-[#B44D3B] border border-[#B44D3B]/30 rounded-md py-3 px-4 flex items-center justify-between text-[14px]">
                        <div class="flex items-center gap-2.5">
                            <x-bi-exclamation-circle class="w-5 h-5 flex-shrink-0" />
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.closest('.flash-alert').remove()" class="text-[#B44D3B] hover:opacity-75 p-1">
                            <x-bi-x class="w-4 h-4" />
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-alert bg-[rgba(180,77,59,0.12)] text-[#B44D3B] border border-[#B44D3B]/30 rounded-md py-3 px-4 text-[14px]">
                        <div class="font-semibold mb-1 flex items-center gap-2">
                            <x-bi-exclamation-circle class="w-5 h-5 flex-shrink-0" />
                            <span>Please correct the errors below:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[13px] ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Content Slot -->
            @yield('content')
        </main>
    </div>

    <!-- Mobile sidebar toggler script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('mobile-menu-button');
            const closeBtn = document.getElementById('close-sidebar-button');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            }

            if (openBtn) openBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if (backdrop) backdrop.addEventListener('click', toggleSidebar);

            // Auto-dismiss session flash alerts after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.flash-alert').forEach(el => {
                    el.style.transition = 'opacity 300ms ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 300);
                });
            }, 5000);
        });
    </script>
</body>
</html>
