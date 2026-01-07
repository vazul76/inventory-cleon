@extends('teknisi.layout')
@section('page-title', 'Kembalikan Alat')
@section('page-subtitle', 'Daftar alat yang sedang dipinjam')

@section('content')

@if($peminjaman->isEmpty())
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
        Tidak ada alat yang sedang dipinjam saat ini.
    </div>
@else
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($peminjaman as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $item->alat->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                            {{ $item->jumlah }} unit
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $item->nama_peminjam }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        {{ $item->tanggal_pinjam->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $item->keterangan ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('teknisi.pengembalian.kembali', $item->id) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin mengembalikan alat ini?')">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                Kembalikan
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection