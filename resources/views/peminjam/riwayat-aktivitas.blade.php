@extends('peminjam.layout')

@section('page-title', 'Riwayat Aktivitas')

@section('content')

    <style>
        /* Filter panel animation */
        .filter-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
            opacity: 0;
        }

        .filter-panel.show {
            max-height: 300px;
            opacity: 1;
        }
    </style>

    <!-- Filter Bar (Filament Style) -->
<div class="mb-6 relative">
    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-600">
            @if($tanggal)
                Menampilkan <span class="font-semibold">{{ $riwayatAlat->count() + $riwayatMaterial->count() }}</span> aktivitas pada <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</span>
            @else
                Menampilkan <span class="font-semibold">{{ $riwayatAlat->count() + $riwayatMaterial->count() }}</span> aktivitas (semua riwayat)
            @endif
        </div>
        <button type="button" id="filterToggle" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition hover:text-blue-600 hover:border-blue-600">
            <i class="fas fa-filter text-xs"></i>
            <span>Filter</span>
        </button>
    </div>
    
    <!-- Filter Panel (Floating Popup) -->
    <div id="filterPanel" class="filter-panel absolute right-0 top-12 z-50 bg-white rounded-lg shadow-xl border border-gray-200 p-3 w-64">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-xs font-semibold text-gray-900">Filters</h3>
            <a href="{{ route('peminjam.riwayat-aktivitas') }}" class="text-xs text-red-600 hover:text-red-700 font-medium">Reset</a>
        </div>
        <form action="{{ route('peminjam.riwayat-aktivitas') }}" method="GET">
            <div class="mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" 
                       class="w-full px-2.5 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md font-medium text-xs transition">
                Terapkan Filter
            </button>
        </form>
    </div>
</div>

    <!-- Riwayat Peminjaman Alat -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-semibold"><i class="fas fa-tools mr-2"></i> Riwayat Peminjaman Alat</h3>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $riwayatAlat->count() }} Aktivitas
            </span>
        </div>

        @if($riwayatAlat->isEmpty())
            <div class="bg-gray-100 border border-gray-300 rounded-lg px-6 py-8 text-center">
                <i class="fas fa-tools text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600">Tidak ada data peminjaman alat pada tanggal ini.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Alat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Nama Tim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($riwayatAlat as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item->alat->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->nama_peminjam }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-semibold">
                                            {{ $item->jumlah }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal_pinjam->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status === 'dipinjam')
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-clock mr-1"></i>Dipinjam
                                            </span>
                                        @else
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>Dikembalikan
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Riwayat Pengambilan Material -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-2xl font-semibold"><i class="fas fa-cube mr-2"></i> Riwayat Pengambilan Material
            </h3>
            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $riwayatMaterial->count() }} Aktivitas
            </span>
        </div>

        @if($riwayatMaterial->isEmpty())
            <div class="bg-gray-100 border border-gray-300 rounded-lg px-6 py-8 text-center">
                <i class="fas fa-cube text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600">Tidak ada data pengambilan material pada tanggal ini.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Nama Tim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal Ambil</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($riwayatMaterial as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $item->material->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->nama_pengambil }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm font-semibold">
                                            {{ $item->jumlah }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal_ambil->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status === 'diambil')
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-minus-circle mr-1"></i>Diambil
                                            </span>
                                        @elseif($item->status === 'dikembalikan')
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i>Dikembalikan
                                            </span>
                                        @else
                                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-semibold">
                                                <i class="fas fa-tools mr-1"></i>Dipakai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Toggle filter panel with smooth animation
        document.getElementById('filterToggle').addEventListener('click', function (e) {
            e.stopPropagation();
            const filterPanel = document.getElementById('filterPanel');
            filterPanel.classList.toggle('show');
        });

        // Close filter panel when clicking outside
        document.addEventListener('click', function (event) {
            const filterPanel = document.getElementById('filterPanel');
            const filterToggle = document.getElementById('filterToggle');

            if (!filterPanel.contains(event.target) && !filterToggle.contains(event.target)) {
                filterPanel.classList.remove('show');
            }
        });
    </script>

@endsection