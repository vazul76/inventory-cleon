@extends('peminjam.layout')

@section('page-title', 'Riwayat Saya')
@section('content')

<!-- Form Pencarian -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form action="{{ route('peminjam.riwayat') }}" method="GET" class="flex gap-4">
        <input type="text" name="nama" value="{{ $nama }}" required
               class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Masukkan nama tim (misal: Tim 1)">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
            Cari
        </button>
    </form>
</div>

@if($nama)
    <!-- Riwayat Tools -->
    <div class="mb-8">
        <h3 class="text-2xl font-semibold mb-4"><i class="fas fa-tools mr-2"></i> Riwayat Peminjaman Tools</h3>
        
        @if($riwayatAlat->isEmpty())
            <div class="bg-gray-100 px-4 py-3 rounded">
                Tidak ada riwayat peminjaman tools. 
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tool</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($riwayatAlat as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->alat->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->tanggal_pinjam->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $item->tanggal_kembali ?  $item->tanggal_kembali->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'dipinjam')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Dipinjam</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Riwayat Perangkat -->
    <div>
        <h3 class="text-2xl font-semibold mb-4">📡 Riwayat Pengambilan Perangkat</h3>
        
        @if($riwayatMaterial->isEmpty())
            <div class="bg-gray-100 px-4 py-3 rounded">
                Tidak ada riwayat pengambilan perangkat.
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perangkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Ambil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($riwayatMaterial as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->material->name }}</td>
                            <td class="px-6 py-4">{{ $item->jumlah }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->tanggal_ambil->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->lokasi_pemasangan ??  '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif
@endsection