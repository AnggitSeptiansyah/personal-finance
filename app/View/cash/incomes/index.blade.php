{{-- resources/views/cash/incomes/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pemasukan Cash')

@section('content')
<div x-data="cashIncomesApp()" x-init="loadData()">

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">Catat semua pemasukan cash Anda</p>
        <button @click="openModal()"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pemasukan
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <template x-if="loading">
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Memuat data...</td></tr>
                </template>
                <template x-if="!loading && items.length === 0">
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada pemasukan cash.</td></tr>
                </template>
                <template x-for="item in items" :key="item.id">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-600" x-text="formatDate(item.date)"></td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium"
                                  x-text="item.cash_income_type?.name || '-'"></span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-green-600" x-text="formatRupiah(item.amount)"></td>
                        <td class="px-6 py-4 text-gray-500" x-text="item.note || '-'"></td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="openModal(item)" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</button>
                            <button @click="deleteItem(item.id)" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        {{-- Pagination --}}
        <div x-show="pagination.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
            <span x-text="`Halaman ${pagination.current_page} dari ${pagination.last_page}`"></span>
            <div class="flex gap-2">
                <button @click="loadData(pagination.current_page - 1)"
                        :disabled="pagination.current_page <= 1"
                        class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-40">‹</button>
                <button @click="loadData(pagination.current_page + 1)"
                        :disabled="pagination.current_page >= pagination.last_page"
                        class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-40">›</button>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
         @click.self="closeModal()">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4"
                x-text="editItem ? 'Edit Pemasukan Cash' : 'Tambah Pemasukan Cash'"></h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pemasukan *</label>
                    <select x-model="form.cash_income_type_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Pilih Jenis --</option>
                        <template x-for="type in types" :key="type.id">
                            <option :value="type.id" x-text="type.name"></option>
                        </template>
                    </select>
                    <p x-show="errors.cash_income_type_id" x-text="errors.cash_income_type_id" class="text-red-500 text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                    <input type="number" x-model="form.amount" placeholder="0" min="1"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p x-show="errors.amount" x-text="errors.amount" class="text-red-500 text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                    <input type="date" x-model="form.date"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p x-show="errors.date" x-text="errors.date" class="text-red-500 text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" x-model="form.note" placeholder="Opsional"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button @click="closeModal()" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Batal</button>
                <button @click="saveItem()" :disabled="saving"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium disabled:opacity-50">
                    <span x-text="saving ? 'Menyimpan...' : 'Simpan'"></span>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function cashIncomesApp() {
    return {
        items: [], types: [], loading: true, saving: false,
        showModal: false, editItem: null, pagination: {},
        form: { cash_income_type_id: '', amount: '', date: new Date().toISOString().slice(0,10), note: '' },
        errors: {},
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,

        async loadData(page = 1) {
            this.loading = true;
            const [incomesRes, typesRes] = await Promise.all([
                fetch(`/api/cash/incomes?page=${page}`, { headers: { 'X-CSRF-TOKEN': this.csrfToken } }),
                fetch('/api/cash/income-types', { headers: { 'X-CSRF-TOKEN': this.csrfToken } })
            ]);
            const incomeData = await incomesRes.json();
            const typeData = await typesRes.json();
            this.items = incomeData.data;
            this.pagination = { current_page: incomeData.current_page, last_page: incomeData.last_page };
            this.types = typeData.data;
            this.loading = false;
        },

        openModal(item = null) {
            this.editItem = item;
            this.form = item
                ? { cash_income_type_id: item.cash_income_type_id, amount: item.amount, date: item.date?.slice(0,10), note: item.note || '' }
                : { cash_income_type_id: '', amount: '', date: new Date().toISOString().slice(0,10), note: '' };
            this.errors = {};
            this.showModal = true;
        },

        closeModal() { this.showModal = false; this.editItem = null; },

        async saveItem() {
            this.saving = true;
            this.errors = {};
            const url = this.editItem ? `/api/cash/incomes/${this.editItem.id}` : '/api/cash/incomes';
            const method = this.editItem ? 'PUT' : 'POST';
            const res = await fetch(url, {
                method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                body: JSON.stringify(this.form)
            });
            const data = await res.json();
            if (!res.ok) { if (data.errors) this.errors = data.errors; }
            else { this.closeModal(); await this.loadData(); }
            this.saving = false;
        },

        async deleteItem(id) {
            if (!confirm('Yakin ingin menghapus pemasukan ini?')) return;
            await fetch(`/api/cash/incomes/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': this.csrfToken } });
            await this.loadData();
        },

        formatRupiah(v) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(v || 0);
        },

        formatDate(d) {
            if (!d) return '-';
            return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }
    }
}
</script>
@endpush