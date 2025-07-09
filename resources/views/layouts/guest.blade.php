<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Welcome - {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.13/24/outline/index.css">
</head>

<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <div class="-ml-1">
                        <h1 class="text-xl font-bold text-gray-900">CatatKas</h1>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Kelola Keuangan Anda
                    <span class="block text-blue-200">dengan Mudah</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                    Aplikasi manajemen keuangan yang membantu Anda mencatat transaksi,
                    memantau pengeluaran, dan membuat laporan keuangan yang detail.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                        Mulai Gratis
                    </a>
                    <a href="#features" class="border border-blue-200 text-white hover:bg-blue-500 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Fitur Unggulan
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Semua yang Anda butuhkan untuk mengelola keuangan dengan efektif
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-lg p-8 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Pencatatan Transaksi</h3>
                    <p class="text-gray-600">
                        Catat semua pemasukan dan pengeluaran dengan mudah.
                        Kategorisasi otomatis untuk memudahkan pelacakan.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-lg p-8 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Laporan Detail</h3>
                    <p class="text-gray-600">
                        Buat laporan keuangan yang komprehensif dengan grafik
                        dan analisis yang mudah dipahami.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-lg p-8 text-center hover:shadow-lg transition-shadow">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Dashboard Interaktif</h3>
                    <p class="text-gray-600">
                        Pantau kondisi keuangan Anda secara real-time dengan
                        dashboard yang informatif dan mudah digunakan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Siap Memulai Perjalanan Keuangan Anda?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan
                mengelola keuangan dengan CatatKas.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="border border-blue-200 text-white hover:bg-blue-500 px-8 py-3 rounded-lg text-lg font-medium transition-colors">
                    Sudah Punya Akun?
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <h3 class="-ml-1 text-xl font-bold">CatatKas</h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Aplikasi manajemen keuangan terpercaya yang membantu Anda
                        mencapai tujuan finansial dengan mudah dan efektif.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('transactions.index') }}" class="hover:text-white transition-colors">Pencatatan Transaksi</a></li>
                        <li><a href="{{ route('reports.index') }}" class="hover:text-white transition-colors">Laporan Keuangan</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Panduan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Dukungan</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} CatatKas. Semua hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>