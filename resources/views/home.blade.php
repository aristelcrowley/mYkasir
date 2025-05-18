<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mYkasir - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif; /* Use Segoe UI or a close alternative */
        }
    </style>
</head>
<body class="bg-gray-100">
    <header>
        <nav class="bg-indigo-800 text-white py-4 px-8 flex justify-between items-center">
            <div class="navbar-left">
                <h1 class="text-xl font-semibold">mYkasir</h1>
            </div>
            <div class="navbar-right flex items-center">
                <a href="/login" class="bg-white text-indigo-800 py-2 px-4 rounded-md font-bold mr-2 hover:bg-gray-100 transition duration-300">Login</a>
                <a href="/register" class="bg-transparent border border-white text-white py-2 px-4 rounded-md font-bold hover:bg-white hover:text-indigo-800 transition duration-300">Register</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="bg-gray-100 py-16 px-8 flex justify-between items-center max-w-6xl mx-auto">
            <div class="hero-content max-w-md">
                <h2 class="text-4xl font-semibold text-gray-800 mb-4">Selamat datang di mYkasir</h2>
                <p class="text-lg text-gray-700 mb-8">Aplikasi kasir sederhana berbasis web dengan Laravel dan AJAX</p>
                <a href="/register" class="bg-indigo-800 text-white py-3 px-6 rounded-md font-bold hover:bg-indigo-700 transition duration-300">Mulai Sekarang</a>
            </div>
            <div class="hero-image">
                <img src="/assets/cashier.png" alt="Cashier Illustration" class="w-72 h-auto">
            </div>
        </section>

        <section class="bg-white py-16 px-6 text-center">
            <h3 class="text-3xl font-semibold text-gray-800 mb-8">Fitur Utama</h3>
            <div class="feature-list flex justify-around flex-wrap gap-8 max-w-6xl mx-auto">
                <div class="feature-item max-w-sm">
                    <h4 class="text-xl font-semibold text-indigo-800 mb-2">Manajemen Produk</h4>
                    <p class="text-gray-700">Tambah, edit, dan hapus produk.</p>
                </div>
                <div class="feature-item max-w-sm">
                    <h4 class="text-xl font-semibold text-indigo-800 mb-2">Transaksi Cepat</h4>
                    <p class="text-gray-700">Lakukan transaksi tanpa refresh halaman.</p>
                </div>
                <div class="feature-item max-w-sm">
                    <h4 class="text-xl font-semibold text-indigo-800 mb-2">Autentikasi Aman</h4>
                    <p class="text-gray-700">Data pribadi anda aman (mungkin).</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-indigo-800 text-white text-center py-9">
        <p>&copy; {{ date('Y') }} mYkasir. All rights reserved.</p>
    </footer>
</body>
</html>
