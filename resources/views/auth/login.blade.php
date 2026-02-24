<x-guest-layout>
    <div class="flex min-h-screen bg-gray-50">

        <!-- Left Side - Branding -->
        <div
            class="items-center justify-center hidden p-12 lg:flex lg:w-1/2 bg-gradient-to-br from-teal-500 via-teal-600 to-teal-700">
            <div class="max-w-lg text-center">
                <!-- Logo -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center p-4 bg-white shadow-xl rounded-2xl">
                        <img src="{{ asset('assets/logo-intira.png') }}" alt="Intira Logo" class="h-16">
                    </div>
                </div>

                <h1 class="mb-4 text-4xl font-bold text-white">
                    Sistem Manajemen SDM
                </h1>
                <p class="mb-8 text-lg text-teal-100">
                    Kelola data karyawan, presensi, dan laporan harian dengan mudah dan efisien
                </p>

                <!-- Feature Highlights -->
                <div class="space-y-4 text-left">
                    <div
                        class="flex items-start gap-3 p-4 transition bg-white rounded-lg shadow-md bg-opacity-10 backdrop-blur-sm hover:bg-opacity-20">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-white rounded-lg bg-opacity-20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Penggajian</h3>
                            <p class="text-sm text-teal-100">Sistem Payroll Karyawan</p>
                        </div>
                    </div>
                    <div
                        class="flex items-start gap-3 p-4 transition bg-white rounded-lg shadow-md bg-opacity-10 backdrop-blur-sm hover:bg-opacity-20">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-white rounded-lg bg-opacity-20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Manajemen Data Karyawan</h3>
                            <p class="text-sm text-teal-100">Kelola data & penempatan cabang</p>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-3 p-4 transition bg-white rounded-lg shadow-md bg-opacity-10 backdrop-blur-sm hover:bg-opacity-20">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-white rounded-lg bg-opacity-20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Monitoring Presensi</h3>
                            <p class="text-sm text-teal-100">Laporan kehadiran & absensi karyawan</p>
                        </div>
                    </div>

                </div>

                <!-- Footer Badge -->
                <div
                    class="flex items-center justify-center gap-2 px-4 py-2 mx-auto mt-8 bg-white rounded-full w-fit bg-opacity-10 backdrop-blur-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="text-sm font-medium text-white">Secure & Trusted Platform</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex items-center justify-center w-full px-6 py-12 lg:w-1/2">
            <div class="w-full max-w-md">

                <!-- Mobile Logo & Title -->
                <div class="mb-8 text-center lg:hidden">
                    <img src="{{ asset('assets/logo-intira.png') }}" alt="Intira Logo" class="h-16 mx-auto mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Sistem SDM Intira</h2>
                    <p class="mt-1 text-sm text-gray-500">Masuk ke akun Anda</p>
                </div>

                <!-- Desktop Title -->
                <div class="hidden mb-8 lg:block">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('assets/icon-intira.png') }}" alt="Icon" class="w-auto h-10">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang</h2>
                            <p class="text-sm text-gray-500">Sistem Manajemen SDM Intira</p>
                        </div>
                    </div>
                    <p class="text-gray-600">Masuk dengan kredensial Anda untuk melanjutkan</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus
                                class="block w-full pl-10 pr-3 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                class="block w-full pl-10 pr-3 py-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>

                        {{-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-medium text-teal-600 hover:text-teal-700">
                                Lupa password?
                            </a>
                        @endif --}}
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full px-4 py-3 text-white transition bg-teal-600 rounded-lg shadow-lg hover:bg-teal-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <span class="flex items-center justify-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Masuk ke Dashboard
                        </span>
                    </button>
                </form>

                <!-- Help Section -->
                <div class="p-4 mt-6 border border-gray-200 rounded-lg bg-gray-50">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Butuh bantuan?</span>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSecdUNCYmTaC4c0goVu4IZgTkmvmYlfnlvwTdAoSwQcZrxD4Q/viewform?usp=publish-editor"
                            class="text-teal-600 hover:text-teal-700">
                            Hubungi Administrator
                        </a>
                    </p>
                </div>

                <!-- Copyright -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500">
                        © {{ date('Y') }} All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
