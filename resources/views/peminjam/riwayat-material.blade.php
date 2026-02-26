@extends('peminjam.layout')

@section('page-title', 'Riwayat Material')

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

<!-- Search & Filter Bar -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6 relative">
<form action="{{ route('peminjam.riwayat-material') }}" method="GET"
      class="flex items-center gap-3">

    <!-- Search -->
    <input type="text"
           name="nama"
           value="{{ $nama ?? '' }}"
           placeholder="Cari nama tim..."
           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg
                  focus:ring-2 focus:ring-blue-500 focus:outline-none">

    <!-- Button Cari -->
    <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white
               px-6 py-2 rounded-lg font-semibold transition">
        Cari
    </button>

    <!-- Button Filter -->
    <button type="button"
        id="filterToggle"
        class="inline-flex items-center gap-2
               px-4 py-2 rounded-lg border border-gray-300
               text-gray-700 hover:bg-gray-50
               hover:text-blue-600 hover:border-blue-600 transition">
        <i class="fas fa-filter text-xs"></i>
        Filter
    </button>

    <!-- FILTER PANEL (MASIH DI DALAM FORM!) -->
    <div id="filterPanel"
         class="filter-panel absolute right-4 top-[72px] z-50
                bg-white rounded-xl shadow-xl border
                border-gray-200 p-4 w-64">

        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-semibold text-gray-900">Filter</h3>
            <a href="{{ route('peminjam.riwayat-material') }}"
               class="text-xs text-red-600 hover:underline">
                Reset
            </a>
        </div>

        <div class="mb-3">
            <label class="block text-xs font-medium text-gray-700 mb-1">
                Tanggal
            </label>
            <input type="date"
                   name="tanggal"
                   value="{{ $tanggal }}"
                   class="w-full px-3 py-2 text-sm border border-gray-300
                          rounded-md focus:ring-1 focus:ring-blue-500">
        </div>

        <!-- INI KUNCINYA -->
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700
                       text-white py-2 rounded-lg text-sm font-semibold">
            Terapkan
        </button>
    </div>
</form>
   <div class="text-sm text-gray-600">
            @if($tanggal)
                Menampilkan
                <span class="font-semibold">{{ $riwayatMaterial->count() }}</span>
                aktivitas pada
                <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                </span>
            @else
                Menampilkan
                <span class="font-semibold">{{ $riwayatMaterial->count() }}</span>
                aktivitas (semua riwayat)
            @endif
    </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/7">Material</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/7">Nama Tim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-22">Jumlah Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-22">Jumlah Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/7">Tanggal Ambil</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/7">Tanggal Kembali</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/7">Kontak Pengambil</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/7">Status</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm font-semibold">
                                            {{ $item->jumlah_dikembalikan }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal_ambil->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y, H:i') : '-' }}
                                    </td>
                                     <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($item->kontak_pengambil)
                                                <a href="https://wa.me/{{ $item->kontak_pengambil }}" 
                                                target="_blank"
                                                class="text-sm text-green-600 hover:underline">
                                                    {{ $item->kontak_pengambil }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </div>
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
                    <div class="px-6 py-4">
                        {{ $riwayatMaterial->links('vendor.pagination.tailwind') }}
                    </div>
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