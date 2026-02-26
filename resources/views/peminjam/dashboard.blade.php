@extends('peminjam.layout')

@section('page-title', 'Dashboard')

@section('content')

<!-- ========== SECTION TOOLS ========== -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-line text-2xl mr-2"></i>
        Statistik
    </h3>

    <!-- Cards Alat:  Kemarin vs Hari Ini -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Kemarin -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Alat Kemarin</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $alatKemarin }}</h4>
                    <p class="text-blue-100 text-xs mt-1">{{ now()->subDay()->format('d M Y') }}</p>
                </div>
                <i class="fas fa-tools text-6xl opacity-20"></i>
            </div>
        </div>

        <!-- Card Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Alat Hari Ini</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $alatHariIni }}</h4>
                    <p class="text-green-100 text-xs mt-1">{{ now()->format('d M Y') }}</p>
                </div>
                <i class="fas fa-tools text-6xl opacity-20"></i>
            </div>
        </div>
    </div>
    <!-- Cards Material:  Kemarin vs Hari Ini -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card Kemarin -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Material Kemarin</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $materialKemarin }}</h4>
                    <p class="text-purple-100 text-xs mt-1">{{ now()->subDay()->format('d M Y') }}</p>
                </div>
                <i class="fas fa-cube text-6xl opacity-20"></i>
            </div>
        </div>

        <!-- Card Hari Ini -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Material Hari Ini</p>
                    <h4 class="text-4xl font-bold mt-2">{{ $materialHariIni }}</h4>
                    <p class="text-orange-100 text-xs mt-1">{{ now()->format('d M Y') }}</p>
                </div>
                <i class="fas fa-cube text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Detail Tools -->
    <div class="bg-white rounded-lg my-6 shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h4 class="font-bold text-gray-800">Detail Update Alat Hari Ini</h4>
        </div>
        
        <div class="p-6">
            @if($allAlatActivities->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada aktivitas alat hari ini. </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/4">Alat</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-20">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/5">Peminjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/5">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($allAlatActivities as $item)
                                <tr class="hover:bg-blue-50">
                                    <td class="px-4 py-3 font-medium">{{ $item->alat->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-blue-600">{{ $item->jumlah }}</span>
                                    </td>
                                    <td class="px-4 py-3">{{ $item->nama_peminjam }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_pinjam->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-plus-circle mr-1"></i> Dipinjam
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

        <!-- Detail Perangkat -->
    <div class="bg-white rounded-lg shadow-md my-6 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h4 class="font-bold text-gray-800">Detail Update Material Hari Ini</h4>
        </div>
        
        <div class="p-6">
            @if($allMaterialActivities->isEmpty())
                <p class="text-gray-500 text-center py-4">Belum ada aktivitas material hari ini. </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/4">Material</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-20">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/5">Pengambil</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/5">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($allMaterialActivities as $item)
                                <tr class="hover:bg-orange-50">
                                    <td class="px-4 py-3 font-medium">{{ $item->material->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-blue-600">{{ $item->jumlah }}</span>
                                    </td>
                                    <td class="px-4 py-3">{{ $item->nama_pengambil }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_ambil->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-arrow-circle-up mr-1"></i> Diambil
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