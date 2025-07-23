@extends('layouts.app')
@section('content')
    <div class="w-full bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Edit Akun Pengguna</h1>

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Pastikan variabel $user tersedia dan memiliki properti 'id' --}}
        {{-- Jika $user tidak ada atau null, ini akan menyebabkan error. --}}
        {{-- Pastikan controller Anda meneruskan objek $user ke view ini. --}}
        <form action="{{ route('pemilik.kelola-akun.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') {{-- Penting untuk metode PUT/PATCH --}}

            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username:</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" id="password" name="password" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">Konfirmasi Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-semibold mb-2">Peran:</label>
                <select id="role" name="role" required class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                    <option value="pemilik" {{ old('role', $user->role) == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="member_token" class="block text-gray-700 text-sm font-semibold mb-2">No Telpon (Opsional)</label>
                <input type="text" id="member_token" name="member_token" class="shadow-sm appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('member_token', $user->member_token) }}">
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out">Simpan Perubahan</button>
                <a href="{{ route('pemilik.kelola-akun.index') }}" class="w-full sm:w-auto text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out">Batal</a>
            </div>
        </form>
    </div>
@endsection