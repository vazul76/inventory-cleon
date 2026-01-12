@extends('peminjam.layout')
@section('page-title', 'Material')

@section('content')

    <div class="bg-white rounded-lg shadow-md p-6">
        @if ($material->isEmpty())
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                Tidak ada material saat ini.
            </div>
        @else
            <!-- Action Bar -->
            <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span id="selected-count" class="text-gray-600">0 material dipilih</span>
                    <button id="btn-ambil-multiple"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
                        disabled>
                        Ambil Material
                    </button>
                </div>

                <form method="GET" action="{{ route('peminjam.material') }}" class="w-full md:w-auto flex justify-end">
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5 5a7.5 7.5 0 0 0 11.65 11.65Z" />
                            </svg>
                        </span>
                        <input type="search" name="q" value="{{ isset($q) ? $q : request('q') }}"
                            placeholder="Cari material..."
                            class="h-10 w-full pl-10 pr-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </form>
            </div>

            <!-- Table Material -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 rounded">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Material</th>
                            <th
                                class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stock</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($material as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    @if ($item->stock > 0)
                                        <input type="checkbox" class="select-material w-4 h-4 text-blue-600 rounded"
                                            value="{{ $item->id }}" data-name="{{ $item->name }}"
                                            data-stock="{{ $item->stock }}">
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                </td>
                                <td class="hidden md:table-cell px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $item->description ?: '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($item->stock > 0)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $item->stock }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            0
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($item->stock > 0)
                                        <button type="button"
                                            class="btn-ambil-single bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1 px-4 rounded"
                                            data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                            data-stock="{{ $item->stock }}">
                                            Ambil
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal Popup -->
    <div id="modal-ambil" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Form Pengambilan Material</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>

            <form id="form-ambil" action="{{ route('peminjam.material.ambil') }}" method="POST">
                @csrf
                <input type="hidden" id="selected-materials-input" name="materials" value="">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tim/Pengambil</label>
                    <input type="text" name="nama_pengambil" id="nama_pengambil" required
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Misal: Tim 1, Tim Field">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                    <input type="text" name="keperluan"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Misal: Pemasangan baru">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material yang Diambil:</label>
                    <div id="selected-materials-list" class="bg-gray-50 rounded p-3 max-h-60 overflow-y-auto">
                        <!-- List akan diisi dengan JavaScript -->
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="cancel-modal"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded">
                        Konfirmasi Ambil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const selectMaterialCheckboxes = document.querySelectorAll('.select-material');
            const btnAmbilMultiple = document.getElementById('btn-ambil-multiple');
            const selectedCountSpan = document.getElementById('selected-count');
            const modal = document.getElementById('modal-ambil');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelModalBtn = document.getElementById('cancel-modal');
            const selectedMaterialsList = document.getElementById('selected-materials-list');
            const formAmbil = document.getElementById('form-ambil');
            const selectedMaterialsInput = document.getElementById('selected-materials-input');

            let selectedMaterials = [];

            // Select All
            selectAllCheckbox.addEventListener('change', function() {
                selectMaterialCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectedMaterials();
            });

            // Individual checkbox
            selectMaterialCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectedMaterials);
            });

            // Update selected materials
            function updateSelectedMaterials() {
                selectedMaterials = [];
                selectMaterialCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        selectedMaterials.push({
                            id: cb.value,
                            name: cb.dataset.name,
                            stock: parseInt(cb.dataset.stock),
                            jumlah: 1
                        });
                    }
                });

                const count = selectedMaterials.length;
                selectedCountSpan.textContent = count + ' material dipilih';
                btnAmbilMultiple.disabled = count === 0;

                // Update select all checkbox
                const allChecked = selectMaterialCheckboxes.length > 0 &&
                    Array.from(selectMaterialCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }

            // Ambil Multiple
            btnAmbilMultiple.addEventListener('click', function() {
                showModal(selectedMaterials);
            });

            // Ambil Single
            document.querySelectorAll('.btn-ambil-single').forEach(btn => {
                btn.addEventListener('click', function() {
                    const singleMaterial = [{
                        id: this.dataset.id,
                        name: this.dataset.name,
                        stock: parseInt(this.dataset.stock),
                        jumlah: 1
                    }];
                    showModal(singleMaterial);
                });
            });

            function showModal(materials) {
                selectedMaterialsList.innerHTML = '';

                materials.forEach((material, index) => {
                    const div = document.createElement('div');
                    div.className = 'flex justify-between items-center mb-2 p-2 bg-white rounded border';
                    div.innerHTML = `
                <div class="flex-1">
                    <span class="font-medium">${material.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(Stock: ${material.stock})</span>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Jumlah:</label>
                    <input type="number" 
                           class="jumlah-input w-20 px-2 py-1 border border-gray-300 rounded text-center"
                           data-index="${index}"
                           min="1" 
                           max="${material.stock}" 
                           value="${material.jumlah}">
                </div>
            `;
                    selectedMaterialsList.appendChild(div);
                });

                // Update jumlah on input change
                document.querySelectorAll('.jumlah-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const index = parseInt(this.dataset.index);
                        materials[index].jumlah = parseInt(this.value) || 1;
                        // Update hidden input setiap kali ada perubahan
                        selectedMaterialsInput.value = JSON.stringify(materials);
                    });

                    input.addEventListener('input', function() {
                        const index = parseInt(this.dataset.index);
                        materials[index].jumlah = parseInt(this.value) || 1;
                        // Update hidden input setiap kali ada perubahan
                        selectedMaterialsInput.value = JSON.stringify(materials);
                    });
                });

                // Store in hidden input
                selectedMaterialsInput.value = JSON.stringify(materials);

                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                formAmbil.reset();
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
