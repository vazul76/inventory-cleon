@extends('peminjam.layout')
@section('page-title', 'Pengembalian Alat')

@section('content')

    <div class="bg-white rounded-lg shadow-md p-6">
        @if ($peminjaman->isEmpty())
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                Tidak ada alat yang sedang dipinjam saat ini.
            </div>
        @else
            <!-- Action Bar -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span id="selected-count" class="text-gray-600">0 alat dipilih</span>
                    <button id="btn-kembalikan-multiple"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
                        disabled>
                        Kembalikan Alat
                    </button>
                </div>

                <form method="GET" action="{{ route('peminjam.pengembalian-alat') }}"
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
                            placeholder="Cari alat/peminjam..."
                            class="h-10 w-full pl-10 pr-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </form>
            </div>

            <!-- Table Pengembalian -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Keterangan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($peminjaman as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="select-peminjaman w-4 h-4 text-blue-600 rounded"
                                        value="{{ $item->id }}" data-alat="{{ $item->alat->name }}"
                                        data-jumlah="{{ $item->jumlah }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->alat->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $item->jumlah }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $item->nama_peminjam }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $item->tanggal_pinjam->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $item->keterangan ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        class="btn-kembalikan-single bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1 px-4 rounded"
                                        data-id="{{ $item->id }}" data-alat="{{ $item->alat->name }}"
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
    <div id="modal-kembalikan" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Konfirmasi Pengembalian Alat</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <form id="form-kembalikan" method="POST">
                @csrf
                <input type="hidden" id="selected-ids-input" name="peminjaman_ids" value="">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alat yang Dikembalikan:</label>
                    <div id="selected-list" class="bg-gray-50 rounded p-3 max-h-60 overflow-y-auto">
                        <!-- List akan diisi dengan JavaScript -->
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Pastikan alat yang dikembalikan dalam kondisi baik.
                            </p>
                        </div>
                    </div>
                </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const selectPeminjamanCheckboxes = document.querySelectorAll('.select-peminjaman');
            const btnKembalikanMultiple = document.getElementById('btn-kembalikan-multiple');
            const selectedCountSpan = document.getElementById('selected-count');
            const modal = document.getElementById('modal-kembalikan');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelModalBtn = document.getElementById('cancel-modal');
            const selectedList = document.getElementById('selected-list');
            const formKembalikan = document.getElementById('form-kembalikan');
            const selectedIdsInput = document.getElementById('selected-ids-input');

            let selectedItems = [];

            // Select All
            selectAllCheckbox.addEventListener('change', function() {
                selectPeminjamanCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelected();
            });

            // Individual checkbox
            selectPeminjamanCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelected);
            });

            // Update selected items
            function updateSelected() {
                selectedItems = [];
                selectPeminjamanCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        selectedItems.push({
                            id: cb.value,
                            alat: cb.dataset.alat,
                            jumlah: cb.dataset.jumlah
                        });
                    }
                });

                const count = selectedItems.length;
                selectedCountSpan.textContent = count + ' alat dipilih';
                btnKembalikanMultiple.disabled = count === 0;

                // Update select all checkbox
                const allChecked = selectPeminjamanCheckboxes.length > 0 &&
                    Array.from(selectPeminjamanCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }

            // Kembalikan Multiple
            btnKembalikanMultiple.addEventListener('click', function() {
                showModal(selectedItems);
            });

            // Kembalikan Single
            document.querySelectorAll('.btn-kembalikan-single').forEach(btn => {
                btn.addEventListener('click', function() {
                    const singleItem = [{
                        id: this.dataset.id,
                        alat: this.dataset.alat,
                        jumlah: this.dataset.jumlah
                    }];
                    showModal(singleItem);
                });
            });

            function showModal(items) {
                selectedList.innerHTML = '';

                items.forEach((item) => {
                    const div = document.createElement('div');
                    div.className = 'flex justify-between items-center mb-2 p-2 bg-white rounded border';
                    div.innerHTML = `
                <div class="flex-1">
                    <span class="font-medium">${item.alat}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-600">${item.jumlah}</span>
                </div>
            `;
                    selectedList.appendChild(div);
                });

                // Set form action for multiple or single
                const ids = items.map(i => i.id);
                const baseUrl = "{{ url('/pengembalian-alat') }}";
                const multipleUrl = "{{ route('peminjam.pengembalian-alat.kembali.multiple') }}";

                if (ids.length === 1) {
                    formKembalikan.action = baseUrl + "/" + ids[0];
                } else {
                    formKembalikan.action = multipleUrl;
                    selectedIdsInput.value = JSON.stringify(ids);
                }

                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                formKembalikan.reset();
            }

            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        });
    </script>

@endsection