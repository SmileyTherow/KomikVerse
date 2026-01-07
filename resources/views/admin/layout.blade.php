<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin Panel') - inkomi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                    <i class="fas fa-book-open text-indigo-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-800">@yield('brand', 'INKOMI Admin')</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user-cog text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ auth()->user()?->name ?? 'Admin Sistem' }}</p>
                        <p class="text-sm text-gray-500">Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full md:w-1/5">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <nav class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 rounded-lg font-medium' : 'text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                        </a>

                        <a href="{{ route('admin.comics.index') }}"
                            class="flex items-center p-3 {{ request()->routeIs('admin.comics.*') ? 'bg-indigo-50 text-indigo-700 rounded-lg font-medium' : 'text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                            <i class="fas fa-book mr-3"></i>Kelola Komik
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center p-3 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 rounded-lg font-medium' : 'text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                            <i class="fas fa-users mr-3"></i>Kelola User
                        </a>

                        <a href="{{ route('admin.borrowings.index') }}"
                            class="flex items-center p-3 {{ request()->routeIs('admin.borrowings.*') ? 'bg-indigo-50 text-indigo-700 rounded-lg font-medium' : 'text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                            <i class="fas fa-history mr-3"></i>Kelola Peminjaman
                        </a>

                        <a href="{{ route('admin.activity') }}"
                            class="flex items-center p-3 {{ request()->routeIs('admin.activity') ? 'bg-indigo-50 text-indigo-700 rounded-lg font-medium' : 'text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                            <i class="fas fa-stream mr-3"></i>Aktivitas Terkini
                        </a>

                        <a href="{{ route('admin.statistics') }}"
                            class="flex items-center p-3 {{ request()->routeIs('admin.statistics') ? 'bg-indigo-50 text-indigo-700 rounded-lg font-medium' : 'text-gray-700 hover:bg-gray-100 rounded-lg' }}">
                            <i class="fas fa-chart-bar mr-3"></i>Statistik
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="mt-6">
                            @csrf
                            <button type="submit"
                                class="flex items-center p-3 text-gray-700 hover:bg-gray-100 rounded-lg w-full">
                                <i class="fas fa-sign-out-alt mr-3"></i>Keluar
                            </button>
                        </form>
                    </nav>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.comics.create') }}"
                            class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-plus text-blue-600"></i>
                            </div>
                            <span>Tambah Komik Baru</span>
                        </a>
                        <a href="{{ route('admin.borrowings.index') }}"
                            class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <span>Approve Peminjaman</span>
                        </a>
                        <a href="{{ route('admin.genres.index') }}"
                            class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg">
                            <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-tags text-pink-600"></i>
                            </div>
                            <span>Kelola Genre</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center p-3 bg-gray-50 hover:bg-gray-100 rounded-lg">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-folder-open text-yellow-600"></i>
                            </div>
                            <span>Kelola Kategori</span>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main -->
            <main class="w-full md:w-4/5">
                @yield('content')
            </main>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-6 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400">© {{ date('Y') }} inkomi</p>
        </div>
    </footer>
</body>

</html>
