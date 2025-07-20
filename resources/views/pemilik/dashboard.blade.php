@extends('layouts.app')

@section('title','Dashboard-Pemilik')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card Jumlah Kecap Masuk -->
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-all duration-300 hover:shadow-lg hover:scale-105">
                <div>
                    <div class="text-sm font-medium text-gray-500">Jumlah Kecap Masuk</div>
                    <div class="text-3xl font-bold text-indigo-700 mt-1">{{ $totalKecapMasuk }}</div>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
            </div>

            <!-- Card Jumlah Kecap Keluar -->
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-all duration-300 hover:shadow-lg hover:scale-105">
                <div>
                    <div class="text-sm font-medium text-gray-500">Jumlah Kecap Keluar</div>
                    <div class="text-3xl font-bold text-green-700 mt-1">{{ $totalKecapKeluar }}</div>
                </div>
                <div class="p-3 bg-green-100 rounded-full text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m7 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h.01"></path></svg>
                </div>
            </div>

            <!-- Card Jumlah stok Sisa -->
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-all duration-300 hover:shadow-lg hover:scale-105">
                <div>
                    <div class="text-sm font-medium text-gray-500">Jumlah Stok Sisa Saat Ini</div>
                    <div class="text-3xl font-bold text-yellow-700 mt-1">{{ $totalStokSisa }}</div>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.801 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.801 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                </div>
            </div>

            <!-- Card Kecap Expired -->
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-all duration-300 hover:shadow-lg hover:scale-105">
                <div>
                    <div class="text-sm font-medium text-gray-500">Kecap Expired</div>
                    <div class="text-3xl font-bold text-purple-700 mt-1">{{ $totalKecapExpired }}</div>
                </div>
                <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
            </div>

            <!-- Card Kecap Yang Akan Expired -->
            <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between transition-all duration-300 hover:shadow-lg hover:scale-105">
                <div>
                    <div class="text-sm font-medium text-gray-500">Kecap Yang Akan Expired</div>
                    <div class="text-3xl font-bold text-red-700 mt-1">{{ $totalKecapAkanExpired }}</div>
                </div>
                <div class="p-3 bg-red-100 rounded-full text-red-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <section class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Informasi Umum</h2>
            <p class="text-gray-700">Selamat datang di Dashboard Pemilik Sistem Inventori Kecap Riboet Purwokerto. Di sini Anda dapat memantau ringkasan stok dan mengelola akun admin.</p>
            <p class="text-gray-700 mt-2">Gunakan menu di samping untuk mengakses fitur-fitur seperti Kelola Akun dan Laporan.</p>
        </section>
@endsection
