<! DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory System - Teknisi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-link {
            transition: all 0.3s;
        }
        .sidebar-link: hover {
            background-color:  rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3b82f6;
        }
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            border-left: 4px solid #3b82f6;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex-shrink-0 overflow-y-auto">
            <!-- Logo/Header -->
            <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h1 class="text-2xl font-bold">📦 Inventory</h1>
                <p class="text-sm text-blue-100 mt-1">System Teknisi</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6">
                <a href="{{ route('teknisi.dashboard') }}" 
                   class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('teknisi.dashboard') ? 'active' : '' }}">
                    <span class="text-xl mr-3">🏠</span>
                    <span>Dashboard</span>
                </a>

                <div class="px-6 py-2 text-xs font-semibold text-gray-400 uppercase mt-4">Alat</div>
                
                <a href="{{ route('teknisi.alat') }}" 
                   class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('teknisi.alat') ? 'active' : '' }}">
                    <span class="text-xl mr-3">🔧</span>
                    <span>Pinjam Alat</span>
                </a>

                <a href="{{ route('teknisi.pengembalian') }}" 
                   class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('teknisi.pengembalian') ? 'active' : '' }}">
                    <span class="text-xl mr-3">↩️</span>
                    <span>Kembalikan Alat</span>
                </a>

                <div class="px-6 py-2 text-xs font-semibold text-gray-400 uppercase mt-4">Material</div>

                <a href="{{ route('teknisi.material') }}" 
                   class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('teknisi.material') ? 'active' : '' }}">
                    <span class="text-xl mr-3">📡</span>
                    <span>Ambil Material</span>
                </a>

                <div class="px-6 py-2 text-xs font-semibold text-gray-400 uppercase mt-4">Lainnya</div>

                <a href="{{ route('teknisi.riwayat') }}" 
                   class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('teknisi.riwayat') ? 'active' : '' }}">
                    <span class="text-xl mr-3">📋</span>
                    <span>Riwayat Saya</span>
                </a>

                <a href="/admin" 
                   class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                    <span class="text-xl mr-3">⚙️</span>
                    <span>Admin Panel</span>
                </a>
            </nav>

            <!-- Footer Sidebar -->
            <div class="absolute bottom-0 w-64 p-4 bg-gray-100 text-center text-sm text-gray-600">
                <p>&copy; 2026 Internal Only</p>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-600">@yield('page-subtitle', '')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ now()->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 shadow-sm">
                        <div class="flex items-center">
                            <span class="text-xl mr-2">✅</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 shadow-sm">
                        <div class="flex items-center">
                            <span class="text-xl mr-2">❌</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>