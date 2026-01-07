@extends('teknisi.layout')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Statistik peminjaman tools dan pengambilan perangkat')

@section('content')

<!-- ========== SECTION TOOLS ========== -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <span class="text-2xl mr-2">🔧</span>
        Statistik Tools
    </h3>

    <!-- Cards:  Kemarin vs Hari Ini -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Kemarin -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Tools Dipinjam Kemarin</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $alatKemarin }}</h4>
                    <p class="text-blue-100 text-xs mt-1">{{ now()->subDay()->format('d M Y') }}</p>
                </div>
                <div class="text-6xl opacity-20">📊</div>
            </div>
        </div>

        <!-- Card Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Tools Dipinjam Hari Ini</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $alatHariIni }}</h4>
                    <p class="text-green-100 text-xs mt-1">{{ now()->format('d M Y') }}</p>
                </div>
                <div class="text-6xl opacity-20">📈</div>
            </div>
        </div>
    </div>

    <!-- Detail Tools -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h4 class="font-semibold text-gray-800">Detail Update Tools Hari Ini</h4>
        </div>
        
        <div class="p-6">
            @if($alatBaru->isEmpty() && $alatDikembalikan->isEmpty() && $alatBelumKembali->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada aktivitas tools hari ini. </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tool</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Tools Baru Dipinjam Hari Ini -->
                            @foreach($alatBaru as $item)
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-3 font-medium">{{ $item->alat->name }}</td>
                                <td class="px-4 py-3"><code class="text-xs">{{ $item->alat->code }}</code></td>
                                <td class="px-4 py-3">{{ $item->nama_peminjam }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_pinjam->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        🆕 Baru Dipinjam
                                    </span>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Tools Dikembalikan Hari Ini -->
                            @foreach($alatDikembalikan as $item)
                            <tr class="hover:bg-green-50">
                                <td class="px-4 py-3 font-medium">{{ $item->alat->name }}</td>
                                <td class="px-4 py-3"><code class="text-xs">{{ $item->alat->code }}</code></td>
                                <td class="px-4 py-3">{{ $item->nama_peminjam }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_kembali->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Dikembalikan
                                    </span>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Tools Belum Dikembalikan (dari hari sebelumnya) -->
                            @foreach($alatBelumKembali as $item)
                                @if(! $item->tanggal_pinjam->isToday()) {{-- Hanya yang bukan hari ini --}}
                                <tr class="hover:bg-yellow-50">
                                    <td class="px-4 py-3 font-medium">{{ $item->alat->name }}</td>
                                    <td class="px-4 py-3"><code class="text-xs">{{ $item->alat->code }}</code></td>
                                    <td class="px-4 py-3">{{ $item->nama_peminjam }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_pinjam->format('d M, H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⏳ Belum Dikembalikan
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========== SECTION PERANGKAT ========== -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <span class="text-2xl mr-2">📡</span>
        Statistik Perangkat
    </h3>

    <!-- Cards:  Kemarin vs Hari Ini -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Kemarin -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Perangkat Diambil Kemarin</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $materialKemarin }}</h4>
                    <p class="text-purple-100 text-xs mt-1">{{ now()->subDay()->format('d M Y') }}</p>
                </div>
                <div class="text-6xl opacity-20">📊</div>
            </div>
        </div>

        <!-- Card Hari Ini -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Perangkat Diambil Hari Ini</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $materialHariIni }}</h4>
                    <p class="text-orange-100 text-xs mt-1">{{ now()->format('d M Y') }}</p>
                </div>
                <div class="text-6xl opacity-20">📈</div>
            </div>
        </div>
    </div>

    <!-- Detail Perangkat -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h4 class="font-semibold text-gray-800">Detail Update Perangkat Hari Ini</h4>
        </div>
        
        <div class="p-6">
            @if($materialBaru->isEmpty() && $materialDiambil->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada aktivitas perangkat hari ini. </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perangkat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengambil</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Perangkat Baru Ditambahkan Hari Ini (dari Admin) -->
                            @foreach($materialBaru as $item)
                            <tr class="hover:bg-blue-50">
                                <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                                <td class="px-4 py-3"><code class="text-xs">{{ $item->code }}</code></td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-blue-600">+{{ $item->stock }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">-</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->created_at->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        🆕 Baru Ditambahkan
                                    </span>
                                </td>
                            </tr>
                            @endforeach

                            <!-- Perangkat yang Diambil Hari Ini -->
                            @foreach($materialDiambil as $item)
                            <tr class="hover:bg-orange-50">
                                <td class="px-4 py-3 font-medium">{{ $item->material->name }}</td>
                                <td class="px-4 py-3"><code class="text-xs">{{ $item->material->code }}</code></td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-orange-600">-{{ $item->jumlah }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $item->nama_pengambil }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_ambil->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        📤 Diambil
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection