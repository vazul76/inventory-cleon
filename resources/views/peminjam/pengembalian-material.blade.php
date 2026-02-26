@extends('peminjam.layout')
@section('page-title', 'Pengembalian Material')

@section('content')

<div class="bg-white rounded-lg shadow-md p-6">
    @if ($pengambilan->isEmpty())
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            Tidak ada material yang sedang diambil saat ini.
        </div>
    @else
        <!-- Action Bar -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-3">
                <span id="selected-count" class="text-gray-600">0 material dipilih</span>
                <button id="btn-kembalikan-multiple"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                    Kembalikan Material
                </button>
            </div>

            <form method="GET" action="{{ route('peminjam.pengembalian-material') }}"
                class="w-full md:w-auto flex justify-end">
                <div class="relative w-full md:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5 5a7.5 7.5 0 0 0 11.65 11.65Z" />
                        </svg>
                    </span>
                    <input type="search" name="q" value="{{ isset($q) ? $q : request('q') }}"
                        placeholder="Cari material/pengambil..."
                        class="h-10 w-full pl-10 pr-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Material
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengambil
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Ambil
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($pengambilan as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <input type="checkbox" class="select-pengambilan w-4 h-4 text-blue-600 rounded"
                            value="{{ $item->id }}"
                            data-material="{{ $item->material->name }}"
                            data-jumlah="{{ $item->jumlah }}">
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $item->material->name }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->jumlah }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $item->nama_pengambil }}</div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">
                            {{ $item->tanggal_ambil->format('d M Y H:i') }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600">{{ $item->keperluan ?? '-' }}</div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <button type="button"
                            class="btn-kembalikan-single bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1 px-4 rounded"
                            data-id="{{ $item->id }}"
                            data-material="{{ $item->material->name }}"
                            data-jumlah="{{ $item->jumlah }}">
                            Kembalikan
                        </button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal Konfirmasi -->
<div id="modal-kembalikan"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-start justify-center z-50 overflow-y-auto">

    <div class="relative mt-20 w-full max-w-2xl bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900">
                Konfirmasi Pengembalian Material
            </h3>
            <button id="close-modal"
                class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form id="form-kembalikan" method="POST">
            @csrf
            <input type="hidden" id="selected-items-input" name="items">
            <input type="hidden" id="jumlah-data" name="jumlah_data">

            <!-- List material -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Material yang Dikembalikan
                </label>

                <div id="selected-list"
                    class="space-y-2 bg-gray-50 border rounded-md p-3 max-h-60 overflow-y-auto">
                    <!-- diisi JS -->
                </div>
            </div>

            <div class="mb-4">
                <label for="keterangan_kembali"
                    class="block text-sm font-medium text-gray-700 mb-1">
                    Keterangan Pengembalian
                </label>

                <textarea
                    id="keterangan_kembali"
                    name="keterangan_kembali"
                    rows="3"
                    placeholder="Contoh: 1 pcs dikembalikan, 1 pcs sudah terpakai"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm
                        focus:outline-none focus:ring-2 focus:ring-blue-500
                        focus:border-blue-500 resize-none"></textarea>
            </div>

            <!-- Info box -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 flex-shrink-0 mt-0.5"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Sisa material yang tidak dikembalikan akan otomatis dianggap
                            <strong>habis terpakai</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="flex justify-end gap-2">
                <button type="button" id="cancel-modal"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                    Konfirmasi Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.select-pengambilan');
    const btnMultiple = document.getElementById('btn-kembalikan-multiple');
    const selectedCount = document.getElementById('selected-count');
    const modal = document.getElementById('modal-kembalikan');
    const selectedList = document.getElementById('selected-list');
    const form = document.getElementById('form-kembalikan');
    const itemsInput = document.getElementById('selected-items-input');
    const jumlahDataInput = document.getElementById('jumlah-data');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelModalBtn = document.getElementById('cancel-modal');

    let selected = [];

    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        update();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', update));

    function update() {
        selected = [];
        checkboxes.forEach(cb => {
            if (cb.checked) {
                selected.push({
                    id: cb.value,
                    material: cb.dataset.material,
                    jumlah: parseInt(cb.dataset.jumlah)
                });
            }
        });
        selectedCount.textContent = `${selected.length} material dipilih`;
        btnMultiple.disabled = selected.length === 0;
    }

    btnMultiple.addEventListener('click', () => showModal(selected));

    document.querySelectorAll('.btn-kembalikan-single').forEach(btn => {
        btn.addEventListener('click', () => {
            showModal([{
                id: btn.dataset.id,
                material: btn.dataset.material,
                jumlah: parseInt(btn.dataset.jumlah)
            }]);
        });
    });

    function showModal(items) {
        selectedList.innerHTML = '';

        items.forEach(item => {
            const div = document.createElement('div');
            div.className = 'border rounded-lg p-4 bg-white';

            div.innerHTML = `
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">
                            ${item.material}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Diambil: ${item.jumlah} unit
                        </p>
                    </div>

                    <div class="w-32">
                        <label class="block text-xs text-gray-600 mb-1">
                            Jumlah dikembalikan
                        </label>
                        <input
                            type="number"
                            min="0"
                            max="${item.jumlah}"
                            value="0"
                            data-id="${item.id}"
                            class="jumlah-kembali w-full px-3 py-2 border border-gray-300 rounded-md text-sm
                                focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            `;

            selectedList.appendChild(div);
        });

        form.action = items.length === 1
            ? `{{ url('/pengembalian-material') }}/${items[0].id}`
            : `{{ route('peminjam.pengembalian-material.kembali.multiple') }}`;

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        form.reset();
    }

    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    form.addEventListener('submit', () => {
        const data = {};
        const items = [];

        document.querySelectorAll('.jumlah-kembali').forEach(input => {
            const id = input.dataset.id;
            const jumlah = parseInt(input.value);
            data[id] = jumlah;
            items.push({ id, jumlah_kembali: jumlah });
        });

        jumlahDataInput.value = JSON.stringify(data);
        itemsInput.value = JSON.stringify(items);
    });
});
</script>

@endsection