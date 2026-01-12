<! DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Inventory System - Peminjam')</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <style>
            /* Sidebar base */
            .sidebar {
                transition: transform 0.3s ease, width 0.3s ease;
            }

            /* Mobile: hidden by default */
            @media (max-width: 767px) {
                .sidebar {
                    width: 18rem;
                    transform: translateX(-100%);
                }

                .sidebar.open {
                    transform: translateX(0);
                }
            }

            /* Desktop: always visible, expand on hover */
            @media (min-width: 768px) {
                .sidebar {
                    width: 4rem;
                    transform: translateX(0) !important;
                }

                .sidebar:hover {
                    width: 18rem;
                }
            }

            .sidebar-link {
                transition: all 0.3s;
                white-space: nowrap;
            }

            .sidebar-link:hover {
                background-color: rgba(59, 130, 246, 0.1);
                border-left: 4px solid #3b82f6;
            }

            .sidebar-link.active {
                background-color: rgba(59, 130, 246, 0.15);
                border-left: 4px solid #3b82f6;
                font-weight: 600;
            }

            .sidebar-icon {
                min-width: 2rem;
                display: inline-block;
            }

            .sidebar-text {
                display: inline-block;
                margin-left: 0.75rem;
                white-space: nowrap;
            }

            /* Desktop: hide text by default, show on hover */
            @media (min-width: 768px) {
                .sidebar-text {
                    opacity: 0;
                    width: 0;
                    margin-left: 0;
                    overflow: hidden;
                }

                .sidebar:hover .sidebar-text {
                    opacity: 1;
                    width: auto;
                    margin-left: 0.75rem;
                }
            }

            /* Divider */
            .sidebar-divider {
                transition: all 0.3s ease;
            }

            @media (min-width: 768px) {
                .sidebar-divider {
                    opacity: 0;
                    max-height: 0;
                    overflow: hidden;
                    padding: 0 1.5rem;
                }

                .sidebar:hover .sidebar-divider {
                    opacity: 1;
                    max-height: 50px;
                    padding: 0.5rem 1.5rem;
                }
            }

            /* Header logo/title */
            @media (min-width: 768px) {
                .sidebar-header-text {
                    opacity: 0;
                    max-height: 0;
                    overflow: hidden;
                }

                .sidebar:hover .sidebar-header-text {
                    opacity: 1;
                    max-height: 100px;
                }
            }

            /* Header logo */
            .sidebar-logo {
                transition: all 0.3s ease;
                height: 3rem;
                width: auto;
            }

            @media (min-width: 768px) {
                .sidebar-logo {
                    height: 3rem;
                    max-width: 3rem;
                    object-fit: contain;
                }

                .sidebar:hover .sidebar-logo {
                    height: 3rem;
                    max-width: 100%;
                }
            }

            /* Footer */
            @media (min-width: 768px) {
                .sidebar-footer {
                    opacity: 0;
                }

                .sidebar:hover .sidebar-footer {
                    opacity: 1;
                }
            }

            /* Overlay for mobile */
            .overlay {
                display: none;
            }

            .overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 30;
            }

            /* Hamburger only on mobile */
            #hamburger {
                display: block;
            }

            @media (min-width: 768px) {
                #hamburger {
                    display: none;
                }
            }
        </style>
    </head>

    <body class="bg-gray-50">
        <!-- Overlay for mobile -->
        <div class="overlay" id="overlay"></div>

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside
                class="sidebar fixed md:relative z-40 h-full bg-white shadow-lg flex-shrink-0 overflow-y-auto overflow-x-hidden"
                id="sidebar">
                <!-- Logo/Header -->
                <div class="p-6 bg-white border-b border-gray-200 flex items-center justify-center">
                    <img src="{{ asset('images/cleon.png') }}" alt="Cleon ISP" class="sidebar-logo">
                </div>

                <!-- Navigation Menu -->
                <nav class="mt-4">
                    <a href="{{ route('peminjam.dashboard') }}"
                        class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon text-lg"><i class="fas fa-home"></i></span>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <div class="sidebar-divider px-6 pt-4 text-xs text-gray-500 uppercase">Peminjaman</div>
                    <a href="{{ route('peminjam.alat') }}"
                        class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('peminjam.alat') ? 'active' : '' }}">
                        <span class="sidebar-icon text-lg"><i class="fas fa-tools"></i></span>
                        <span class="sidebar-text">Alat</span>
                    </a>
                    <a href="{{ route('peminjam.material') }}"
                        class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('peminjam.material') ? 'active' : '' }}">
                        <span class="sidebar-icon text-lg"><i class="fas fa-cube"></i></span>
                        <span class="sidebar-text">Material</span>
                    </a>

                    <div class="sidebar-divider px-6 pt-4 text-xs text-gray-500 uppercase">Pengembalian</div>
                    <a href="{{ route('peminjam.pengembalian-alat') }}"
                        class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('peminjam.pengembalian-alat') ? 'active' : '' }}">
                        <span class="sidebar-icon text-lg"><i class="fas fa-toolbox"></i></span>
                        <span class="sidebar-text">Pengembalian Alat</span>
                    </a>
                    <a href="{{ route('peminjam.pengembalian-material') }}"
                        class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('peminjam.pengembalian-material') ? 'active' : '' }}">
                        <span class="sidebar-icon text-lg"><i class="fas fa-box-open"></i></span>
                        <span class="sidebar-text">Pengembalian Material</span>
                    </a>

                    <div class="sidebar-divider px-6 pt-4 text-xs text-gray-500 uppercase">Riwayat</div>
                    <a href="{{ route('peminjam.riwayat-aktivitas') }}"
                        class="sidebar-link flex items-center px-6 py-3 text-gray-700 {{ request()->routeIs('peminjam.riwayat-aktivitas') ? 'active' : '' }}">
                        <span class="sidebar-icon text-lg"><i class="fas fa-history"></i></span>
                        <span class="sidebar-text">Riwayat Aktivitas</span>
                    </a>
                </nav>

                <!-- Footer Sidebar -->
                <div class="sidebar-footer absolute bottom-0 w-full p-4 bg-white-100 text-center text-sm text-gray-600">
                    <p>© Cleon-2026</p>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-white shadow-sm z-10">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div class="flex items-center">
                            <!-- Hamburger Button -->
                            <button id="hamburger" class="mr-4 text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                                <p class="text-sm text-gray-600">@yield('page-subtitle', '')</p>
                            </div>
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
                                <i class="fas fa-check-circle text-xl mr-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-times-circle text-xl mr-2"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');
            const overlay = document.getElementById('overlay');

            // Toggle sidebar (mobile only)
            hamburger.addEventListener('click', function () {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            });

            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            });
        </script>
    </body>

    </html>