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
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
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
                            class="h-10 w-full pl-10 pr-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" />
                    </div>
                </form>
            </div>

            <!-- Table Pengembalian -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-green-600 rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Material</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengambil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Ambil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Keperluan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pengambilan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="select-pengambilan w-4 h-4 text-green-600 rounded"
                                        value="{{ $item->id }}" data-material="{{ $item->material->name }}"
                                        data-jumlah="{{ $item->jumlah }}">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->material->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $item->jumlah }} unit
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $item->nama_pengambil }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $item->tanggal_ambil->format('d M Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $item->keperluan ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        class="btn-kembalikan-single bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-1 px-4 rounded"
                                        data-id="{{ $item->id }}" data-material="{{ $item->material->name }}"
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
                <h3 class="text-xl font-semibold text-gray-900">Konfirmasi Pengembalian Material</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <form id="form-kembalikan" method="POST">
                @csrf
                <input type="hidden" id="selected-items-input" name="items" value="">
                <input type="hidden" id="jumlah-data" value="">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material yang Dikembalikan:</label>
                    <div id="selected-list" class="space-y-2">
                        <!-- List akan diisi dengan JavaScript -->
                    </div>
                </div>

                <!-- Warning Box (hidden by default) -->
                <div id="warning-box" class="hidden mb-4">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r animate-pulse">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Peringatan!</h3>
                                <div id="warning-message" class="mt-2 text-sm text-red-700">
                                    <!-- Warning message akan diisi dengan JavaScript -->
                                </div>
                            </div>
                        </div>
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
                                Material yang dikembalikan akan ditambahkan kembali ke stock.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="cancel-modal"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded">
                        Batal
                    </button>
                    <button type="submit" id="submit-button"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const selectPengambilanCheckboxes = document.querySelectorAll('.select-pengambilan');
            const btnKembalikanMultiple = document.getElementById('btn-kembalikan-multiple');
            const selectedCountSpan = document.getElementById('selected-count');
            const modal = document.getElementById('modal-kembalikan');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelModalBtn = document.getElementById('cancel-modal');
            const selectedList = document.getElementById('selected-list');
            const formKembalikan = document.getElementById('form-kembalikan');
            const selectedItemsInput = document.getElementById('selected-items-input');
            const warningBox = document.getElementById('warning-box');
            const warningMessage = document.getElementById('warning-message');

            let selectedItems = [];

            // Select All
            selectAllCheckbox.addEventListener('change', function() {
                selectPengambilanCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelected();
            });

            // Individual checkbox
            selectPengambilanCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelected);
            });

            // Update selected items
            function updateSelected() {
                selectedItems = [];
                selectPengambilanCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        selectedItems.push({
                            id: cb.value,
                            material: cb.dataset.material,
                            jumlah: parseInt(cb.dataset.jumlah)
                        });
                    }
                });

                const count = selectedItems.length;
                selectedCountSpan.textContent = count + ' material dipilih';
                btnKembalikanMultiple.disabled = count === 0;

                // Update select all checkbox
                const allChecked = selectPengambilanCheckboxes.length > 0 &&
                    Array.from(selectPengambilanCheckboxes).every(cb => cb.checked);
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
                        material: this.dataset.material,
                        jumlah: parseInt(this.dataset.jumlah)
                    }];
                    showModal(singleItem);
                });
            });

            function showModal(items) {
                selectedList.innerHTML = '';
                warningBox.classList.add('hidden');

                items.forEach((item) => {
                    const div = document.createElement('div');
                    div.className = 'flex justify-between items-center p-3 bg-white rounded border';
                    div.innerHTML = `
                <div class="flex-1">
                    <span class="font-medium text-gray-900">${item.material}</span>
                    <div class="text-xs text-gray-500 mt-1">Jumlah diambil: ${item.jumlah} unit</div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center">
                        <label class="text-xs text-gray-600 mr-2">Jumlah kembali:</label>
                        <input type="number" 
                            class="jumlah-kembali w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-green-500" 
                            data-id="${item.id}"
                            data-max="${item.jumlah}"
                            data-material="${item.material}"
                            value="${item.jumlah}" 
                            min="1"
                            required>
                    </div>
                    <span class="text-xs text-gray-500">unit</span>
                </div>
            `;
                    selectedList.appendChild(div);
                });

                // Add event listeners to all jumlah inputs
                document.querySelectorAll('.jumlah-kembali').forEach(input => {
                    input.addEventListener('input', validateJumlah);
                    input.addEventListener('change', validateJumlah);
                });

                // Set form action
                const baseUrl = "{{ url('/pengembalian-material') }}";
                const multipleUrl = "{{ route('peminjam.pengembalian-material.kembali.multiple') }}";

                if (items.length === 1) {
                    formKembalikan.action = baseUrl + "/" + items[0].id;
                } else {
                    formKembalikan.action = multipleUrl;
                }

                modal.classList.remove('hidden');
            }

            function validateJumlah() {
                const inputs = document.querySelectorAll('.jumlah-kembali');
                let excessItems = [];

                inputs.forEach(input => {
                    const jumlahKembali = parseInt(input.value) || 0;
                    const maxJumlah = parseInt(input.dataset.max);
                    const namaMaterial = input.dataset.material;

                    if (jumlahKembali > maxJumlah) {
                        excessItems.push({
                            material: namaMaterial,
                            diambil: maxJumlah,
                            kembali: jumlahKembali,
                            excess: jumlahKembali - maxJumlah
                        });
                        input.classList.add('border-red-500', 'bg-red-50');
                        input.classList.remove('border-gray-300');
                    } else {
                        input.classList.remove('border-red-500', 'bg-red-50');
                        input.classList.add('border-gray-300');
                    }
                });

                if (excessItems.length > 0) {
                    warningBox.classList.remove('hidden');
                    
                    let warningHtml = '<ul class="list-disc list-inside space-y-1">';
                    excessItems.forEach(item => {
                        warningHtml += `<li><strong>${item.material}</strong>: Diambil ${item.diambil} unit, akan dikembalikan ${item.kembali} unit (kelebihan ${item.excess} unit)</li>`;
                    });
                    warningHtml += '</ul>';
                    warningMessage.innerHTML = warningHtml;
                } else {
                    warningBox.classList.add('hidden');
                }
            }

            // Handle form submission
            formKembalikan.addEventListener('submit', function(e) {

                // Collect jumlah data
                const jumlahData = {};
                const itemsData = [];
                
                document.querySelectorAll('.jumlah-kembali').forEach(input => {
                    const id = input.dataset.id;
                    const jumlah = parseInt(input.value);
                    jumlahData[id] = jumlah;
                    itemsData.push({
                        id: id,
                        jumlah_kembali: jumlah
                    });
                });
                
                document.getElementById('jumlah-data').value = JSON.stringify(jumlahData);
                selectedItemsInput.value = JSON.stringify(itemsData);
            });

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