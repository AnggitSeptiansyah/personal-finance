{{-- resources/views/cash/income-types/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Jenis Pemasukan Cash')

@section('content')
<div x-data="incomeTypesApp()" x-init="loadData()">

    {{-- Header + Add Button --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">Kelola jenis-jenis pemasukan cash Anda</p>
        <button @click="openModal()"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jenis
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <template x-if="loading">
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Memuat data...</td></tr>
                </template>
                <template x-if="!loading && items.length === 0">
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada jenis pemasukan. Tambahkan sekarang!</td></tr>
                </template>
                <template x-for="(item, i) in items" :key="item.id">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500" x-text="i+1"></td>
                        <td class="px-6 py-4 font-medium text-gray-800" x-text="item.name"></td>
                        <td class="px-6 py-4 text-gray-500" x-text="item.description || '-'"></td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="openModal(item)"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium transition">Edit</button>
                            <button @click="deleteItem(item.id)"
                                    class="text-red-500 hover:text-red-700 text-xs font-medium transition">Hapus</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
         @click.self="closeModal()">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4"
                x-text="editItem ? 'Edit Jenis Pemasukan' : 'Tambah Jenis Pemasukan'"></h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                    <input type="text" x-model="form.name" placeholder="Contoh: Gaji Bulanan"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p x-show="errors.name" x-text="errors.name" class="text-red-500 text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" x-model="form.description" placeholder="Opsional"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button @click="closeModal()"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition">Batal</button>
                <button @click="saveItem()" :disabled="saving"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition disabled:opacity-50">
                    <span x-text="saving ? 'Menyimpan...' : 'Simpan'"></span>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function incomeTypesApp() {
    return {
        items: [], loading: true, saving: false,
        showModal: false, editItem: null,
        form: { name: '', description: '' },
        errors: {},
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,

        async loadData() {
            this.loading = true;
            const res = await fetch('/api/cash/income-types', {
                headers: { 'X-CSRF-TOKEN': this.csrfToken }
            });
            const data = await res.json();
            this.items = data.data;
            this.loading = false;
        },

        openModal(item = null) {
            this.editItem = item;
            this.form = item ? { name: item.name, description: item.description || '' } : { name: '', description: '' };
            this.errors = {};
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editItem = null;
        },

        async saveItem() {
            this.saving = true;
            this.errors = {};
            try {
                const url = this.editItem
                    ? `/api/cash/income-types/${this.editItem.id}`
                    : '/api/cash/income-types';
                const method = this.editItem ? 'PUT' : 'POST';
                const res = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                    body: JSON.stringify(this.form)
                });
                const data = await res.json();
                if (!res.ok) {
                    if (data.errors) this.errors = data.errors;
                } else {
                    this.closeModal();
                    await this.loadData();
                }
            } catch(e) { console.error(e); }
            this.saving = false;
        },

        async deleteItem(id) {
            if (!confirm('Yakin ingin menghapus jenis pemasukan ini?')) return;
            await fetch(`/api/cash/income-types/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': this.csrfToken }
            });
            await this.loadData();
        }
    }
}
</script>
@endpush