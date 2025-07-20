<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventori Kecap Riboet</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans antialiased">
<div class="flex flex-col md:flex-row h-screen">
    @if (Auth::check()) 
    <!-- Mobile Header & Hamburger Menu -->
        <header class="bg-gray-800 text-white p-4 flex justify-between items-center md:hidden">
            <div class="text-xl font-bold">Kecap Riboet</div>
            <button id="sidebarToggle" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </header>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white p-6 flex flex-col rounded-r-xl shadow-lg
                                   transform -translate-x-full md:translate-x-0 md:relative md:flex md:h-auto md:rounded-r-xl md:shadow-lg
                                   z-50">
            <div class="text-2xl font-bold mb-8 text-center">Kecap Riboet</div>
            <nav class="flex-grow">
                <ul>
                    <li class="mb-4">
                        <!-- Dashboard Link -->
                        <a href="{{ Auth::user()->role == 'pemilik' ? route('pemilik.dashboard') : route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2 2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    @if (Auth::user()->role == 'admin')   
                    <li class="mb-4">
                    <a href="{{ route('admin.stok_masuk.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.stok_masuk.*') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2zm-11 0h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Stok Masuk
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.master_stok.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.master_stok.*') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                        </svg>
                        Kelola Stok
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('admin.stok_keluar.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.stok_keluar.*') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Stok Keluar
                    </a>
                </li>
                    @endif
                @if (Auth::user()->role == 'pemilik')
                <li class="mb-4">
                    <!-- Kelola Akun Link -->
                    <a href="{{ route('pemilik.kelola-akun.index') }}" class="flex items-center p-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2zm-11 0h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Kelola Akun
                    </a>
                </li>
                @endif
                    <!-- Menu Laporan dengan Sub-menu -->
                    <li class="mb-4">
                        <details class="group">
                            <summary class="flex items-center p-3 rounded-lg cursor-pointer transition duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <span>Laporan</span>
                                <span class="ml-auto transform transition-transform duration-200 group-open:rotate-90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </span>
                            </summary>
                            <ul class="pl-8 mt-2 space-y-2">
                                <li>
                                    <a href="{{ Auth::user()->role == 'pemilik' ? route('pemilik.laporan.index') : route('admin.laporan.index') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.laporan.index') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        Laporan Akhir
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ Auth::user()->role == 'pemilik' ? route('pemilik.laporan.expired') : route('admin.laporan.expired') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.laporan.expired') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        Laporan Expired
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </nav>
        </aside>

    <div id="sidebarBackdrop" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>
    @endif

    <!-- Main Content -->
      
    <main class="flex-1 p-8 overflow-y-auto">
        @if (Auth::check())    
        <header class="flex flex-col sm:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-300">
            @if (Auth::user()->role == 'pemilik')
                <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Dashboard {{ Auth::user()->username }}</h1>
            @elseif (Auth::user()->role == 'admin')
                <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Dashboard {{ Auth::user()->username }}</h1>
            @endif
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4"> 
                    <div class="text-gray-600">
                        Halo, <span class="font-semibold">{{ Auth::user()->username }}</span> (<span class="capitalize">{{ Auth::user()->role }}</span>)
                    </div>
                    <!-- Tombol Logout di pojok kanan atas -->
                    <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center p-2 rounded-lg bg-red-600 text-white transition duration-200 hover:bg-red-700">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
                </div>
            </header>
        @endif
        
            @yield('content')
        
    </main>
</div>

<script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        // Function to open sidebar
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            sidebarBackdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
        }

        // Function to close sidebar
        function closeSidebar() {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Toggle sidebar on button click
        sidebarToggle.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        // Close sidebar when clicking outside (on backdrop)
        sidebarBackdrop.addEventListener('click', () => {
            closeSidebar();
        });

        // Close sidebar when a navigation link is clicked (optional, but good for UX)
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                // Only close if on a mobile view (sidebar is 'fixed')
                if (window.innerWidth < 768) { // Tailwind's 'md' breakpoint is 768px
                    closeSidebar();
                }
            });
        });

        // Handle window resize to ensure correct sidebar state on desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) { // If resized to desktop view
                sidebar.classList.remove('-translate-x-full', 'translate-x-0');
                sidebar.classList.add('md:translate-x-0'); // Ensure it's visible on desktop
                sidebarBackdrop.classList.add('hidden'); // Hide backdrop
                document.body.style.overflow = ''; // Restore scrolling
            } else { // If resized to mobile view
                // If sidebar was open, keep it open, otherwise keep it hidden
                if (!sidebar.classList.contains('translate-x-0')) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });

        // Initial check on load for desktop view
        window.addEventListener('load', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
            }
        });
    </script>
    {{-- <div id="app">
        @yield('content')
    </div> --}}

    {{-- @stack('scripts')  --}}
</body>
</html>