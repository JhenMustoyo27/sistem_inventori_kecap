
<aside class="w-64 bg-gray-800 text-white p-6 flex flex-col rounded-r-xl shadow-lg">
    <div class="text-2xl font-bold mb-8 text-center">Kecap Riboet</div>
    <nav class="flex-grow">
        <ul>
            <li class="mb-4">
                <a href="{{ route('pemilik.dashboard') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('pemilik.dashboard') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2 2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="mb-4">
                <a href="{{ route('pemilik.kelola_akun.index') }}" class="flex items-center p-3 rounded-lg {{ request()->routeIs('pemilik.kelola_akun.index') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2zm-11 0h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Kelola Akun
                </a>
            </li>
            <li class="mb-4">
                <details class="group" {{ request()->routeIs('pemilik.laporan.*') ? 'open' : '' }}>
                    <summary class="flex items-center p-3 rounded-lg cursor-pointer {{ request()->routeIs('pemilik.laporan.*') ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 6h.01m3 0h3m-6 4h.01m3 0h3"></path>
                        </svg>
                        <span>Laporan</span>
                        <span class="ml-auto transform transition-transform group-open:rotate-90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </summary>
                    <ul class="pl-8 mt-2 space-y-2">
                        <li>
                            <a href="{{ route('pemilik.laporan.index') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('pemilik.laporan.index') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                Laporan Akhir
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pemilik.laporan.expired') }}" class="flex items-center p-2 rounded-lg {{ request()->routeIs('pemilik.laporan.expired') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                Laporan Expired
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>
    </nav>
    <div class="mt-auto">
        {{-- <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center p-3 rounded-lg bg-red-600 text-white hover:bg-red-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form> --}}
    </div>
</aside>
