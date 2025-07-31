@extends('layouts.app')
@section('title', 'Login Page')
@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <div>
            <h2 class="text-2xl font-bold text-center text-gray-800">Sistem Inventori Kecap</h2>
            <h2 class="text-2xl font-bold text-center -pt-8 text-gray-800">Putra Riboet</h2>
        </div>
        <p class="text-center text-gray-600">Silakan login ke akun Anda</p>
        
        <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
            @csrf
            <div>
                <label for="username" class="text-sm font-medium text-gray-700">Username</label>
                <input id="username" name="username" type="text" required
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('username') }}">
                @error('username')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input id="password" name="password" type="password" required
                           class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="button" id="togglePassword" class="border border-gray-300 absolute inset-y-0 end-0 flex items-center px-3 text-gray-500">
                        <svg id="eyeIcon" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg id="eyeSlashIcon" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L6.228 6.228" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div>
                <button type="submit"
                        class="w-full px-4 py-2 font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    togglePasswordButton.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the icon visibility
        eyeIcon.classList.toggle('hidden');
        eyeSlashIcon.classList.toggle('hidden');
    });
</script>
@endsection