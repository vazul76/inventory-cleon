@extends('teknisi.layout')
@section('page-title', 'Pinjam Tools')
@section('page-subtitle', 'Pilih tools yang ingin dipinjam untuk pekerjaan lapangan')

@section('content')

@if($alats->isEmpty())
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        Tidak ada tools yang tersedia saat ini.
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($alats as $alat)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($alat->image)
                <img src="{{ Storage::url($alat->image) }}" alt="{{ $alat->name }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-6xl">🔧</span>
                </div>
            @endif
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-semibold">{{ $alat->name }}</h3>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-semibold">
                        {{ $alat->available }} / {{ $alat->quantity }} Tersedia
                    </span>
                </div>
                
                <p class="text-gray-600 text-sm mb-1">Kode: <span class="font-mono">{{ $alat->code }}</span></p>
                <p class="text-gray-600 text-sm mb-4">Kategori: {{ $alat->category->name }}</p>
                
                @if($alat->description)
                    <p class="text-gray-700 text-sm mb-4">{{ $alat->description }}</p>
                @endif

                <!-- Form Pinjam -->
                <form action="{{ route('teknisi.alat.pinjam') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $alat->id }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tim/Peminjam</label>
                        <input type="text" name="nama_peminjam" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Misal: Tim 1, Tim Field">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah" required min="1" max="{{ $alat->available }}" value="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Tersedia: <strong>{{ $alat->available }}</strong> unit</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus: ring-2 focus:ring-blue-500"
                                  placeholder="Keperluan peminjaman..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        Pinjam Tool Ini
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection