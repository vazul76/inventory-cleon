@extends('teknisi.layout')

@section('page-title', 'Ambil Perangkat')
@section('page-subtitle', 'Pilih perangkat yang ingin diambil (Router, HTB, Switch, dll)')

@section('content')


@if($material->isEmpty())
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
        Tidak ada perangkat dengan stock tersedia saat ini.
    </div>
@else
    <div class="grid grid-cols-1 md: grid-cols-2 lg: grid-cols-3 gap-6">
        @foreach($material as $item)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($item->image)
                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <span class="text-6xl">📡</span>
                </div>
            @endif
            
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-semibold">{{ $item->name }}</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-semibold">
                        Stock: {{ $item->stock }}
                    </span>
                </div>
                
                <p class="text-gray-600 text-sm mb-1">Kode: <span class="font-mono">{{ $item->code }}</span></p>
                <p class="text-gray-600 text-sm mb-4">Kategori: {{ $item->category->name }}</p>
                
                @if($item->description)
                    <p class="text-gray-700 text-sm mb-4">{{ $item->description }}</p>
                @endif

                <!-- Form Ambil -->
                <form action="{{ route('teknisi.material.ambil') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="perangkat_id" value="{{ $item->id }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tim/Pengambil</label>
                        <input type="text" name="nama_pengambil" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus: outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Misal: Tim 1, Tim Field">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah" required min="1" max="{{ $item->stock }}" value="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus: ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                        <input type="text" name="keperluan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Misal: Pemasangan baru">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pemasangan</label>
                        <input type="text" name="lokasi_pemasangan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus: outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Misal: Jl. Merdeka No.123">
                    </div>

                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded">
                        Ambil Perangkat
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection