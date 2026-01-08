@extends('teknisi.layout')
@section('page-title', 'Alat')

@section('content')

<div class="bg-white rounded-lg shadow-md p-6">
    @if($alats->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            Tidak ada alat yang tersedia saat ini.
        </div>
    @else
        <!-- Action Bar -->
        <div class="mb-4 flex justify-between items-center">
            <div>
                <span id="selected-count" class="text-gray-600">0 alat dipilih</span>
            </div>
            <button id="btn-pinjam-multiple" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled>
                Pinjam Alat
            </button>
        </div>

        <!-- Table Alat -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Alat</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tersedia</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($alats as $alat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($alat->available > 0)
                                <input type="checkbox" class="select-alat w-4 h-4 text-blue-600 rounded" 
                                       value="{{ $alat->id }}"
                                       data-name="{{ $alat->name }}"
                                       data-available="{{ $alat->available }}">
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $alat->name }}</div>
                        </td>
                        <td class="hidden md:table-cell px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $alat->description ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($alat->available > 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $alat->available }} unit
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    0 unit
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($alat->available > 0)
                                <button type="button" 
                                        class="btn-pinjam-single bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-1 px-4 rounded"
                                        data-id="{{ $alat->id }}"
                                        data-name="{{ $alat->name }}"
                                        data-available="{{ $alat->available }}">
                                    Pinjam
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
<div id="modal-pinjam" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-900">Form Peminjaman Alat</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>
        
        <form id="form-pinjam" action="{{ route('teknisi.alat.pinjam') }}" method="POST">
            @csrf
            <input type="hidden" id="selected-alats-input" name="alats" value="">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tim/Peminjam</label>
                <input type="text" name="nama_peminjam" id="nama_peminjam" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Misal: Tim 1, Tim Field">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Keperluan peminjaman..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alat yang Dipinjam:</label>
                <div id="selected-alats-list" class="bg-gray-50 rounded p-3 max-h-60 overflow-y-auto">
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
                    Konfirmasi Pinjam
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const selectAlatCheckboxes = document.querySelectorAll('.select-alat');
    const btnPinjamMultiple = document.getElementById('btn-pinjam-multiple');
    const selectedCountSpan = document.getElementById('selected-count');
    const modal = document.getElementById('modal-pinjam');
    const closeModalBtn = document.getElementById('close-modal');
    const cancelModalBtn = document.getElementById('cancel-modal');
    const selectedAlatsList = document.getElementById('selected-alats-list');
    const formPinjam = document.getElementById('form-pinjam');
    const selectedAlatsInput = document.getElementById('selected-alats-input');
    
    let selectedAlats = [];

    // Select All
    selectAllCheckbox.addEventListener('change', function() {
        selectAlatCheckboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectedAlats();
    });

    // Individual checkbox
    selectAlatCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedAlats);
    });

    // Update selected alats
    function updateSelectedAlats() {
        selectedAlats = [];
        selectAlatCheckboxes.forEach(cb => {
            if (cb.checked) {
                selectedAlats.push({
                    id: cb.value,
                    name: cb.dataset.name,
                    available: parseInt(cb.dataset.available),
                    jumlah: 1
                });
            }
        });

        const count = selectedAlats.length;
        selectedCountSpan.textContent = count + ' alat dipilih';
        btnPinjamMultiple.disabled = count === 0;

        // Update select all checkbox
        const allChecked = selectAlatCheckboxes.length > 0 && 
                          Array.from(selectAlatCheckboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
    }

    // Pinjam Multiple
    btnPinjamMultiple.addEventListener('click', function() {
        showModal(selectedAlats);
    });

    // Pinjam Single
    document.querySelectorAll('.btn-pinjam-single').forEach(btn => {
        btn.addEventListener('click', function() {
            const singleAlat = [{
                id: this.dataset.id,
                name: this.dataset.name,
                available: parseInt(this.dataset.available),
                jumlah: 1
            }];
            showModal(singleAlat);
        });
    });

    function showModal(alats) {
        selectedAlatsList.innerHTML = '';
        
        alats.forEach((alat, index) => {
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center mb-2 p-2 bg-white rounded border';
            div.innerHTML = `
                <div class="flex-1">
                    <span class="font-medium">${alat.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(Tersedia: ${alat.available})</span>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Jumlah:</label>
                    <input type="number" 
                           class="jumlah-input w-20 px-2 py-1 border border-gray-300 rounded text-center"
                           data-index="${index}"
                           min="1" 
                           max="${alat.available}" 
                           value="${alat.jumlah}">
                </div>
            `;
            selectedAlatsList.appendChild(div);
        });

        // Update jumlah on input change
        document.querySelectorAll('.jumlah-input').forEach(input => {
            input.addEventListener('change', function() {
                const index = parseInt(this.dataset.index);
                alats[index].jumlah = parseInt(this.value) || 1;
                // Update hidden input setiap kali ada perubahan
                selectedAlatsInput.value = JSON.stringify(alats);
            });
            
            input.addEventListener('input', function() {
                const index = parseInt(this.dataset.index);
                alats[index].jumlah = parseInt(this.value) || 1;
                // Update hidden input setiap kali ada perubahan
                selectedAlatsInput.value = JSON.stringify(alats);
            });
        });

        // Store in hidden input
        selectedAlatsInput.value = JSON.stringify(alats);
        
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        formPinjam.reset();
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
