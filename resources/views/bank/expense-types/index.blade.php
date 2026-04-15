@extends('layouts.app')
@section('title', 'Jenis Pengeluaran Bank')
@section('subtitle', 'Kelola kategori pengeluaran saldo bank Anda — berlaku untuk semua akun bank')

@section('content')
{{-- Identik dengan bank/income-types, ganti apiUrl dan label --}}
<div x-data="typeApp('/api/bank/expense-types')" x-init="load()">

    <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700 flex items-start gap-2">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Jenis pengeluaran yang dibuat di sini berlaku untuk <strong>semua akun bank</strong> Anda. Contoh: "Pengisian E-Wallet" bisa dipakai untuk transaksi dari BCA, BRI, maupun BNI sekaligus.</span>
    </div>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">Total <span class="font-bold text-slate-700" x-text="items.length"></span> jenis terdaftar</p>
        <button @click="openModal()"
                class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Jenis
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div x-show="loading" class="flex items-center justify-center py-16">
            <svg class="w-8 h-8 animate-spin text-slate-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <div x-show="!loading && items.length===0" class="flex flex-col items-center py-16 text-slate-400">
            <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            <p class="text-sm font-medium">Belum ada jenis pengeluaran bank</p>
            <p class="text-xs mt-1">Contoh: Listrik, Internet, Pengisian E-Wallet</p>
        </div>
        <table x-show="!loading && items.length>0" class="w-full">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <template x-for="(item,i) in items" :key="item.id">
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        <td class="px-6 py-4 text-sm text-slate-400" x-text="i+1"></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-slate-800" x-text="item.name"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500" x-text="item.description||'—'"></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openModal(item)" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>Edit
                                </button>
                                <button @click="del(item)" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-800" x-text="editItem?'Edit Jenis Pengeluaran':'Tambah Jenis Pengeluaran'"></h3>
                    <p class="text-xs text-slate-400 mt-0.5">Bank — berlaku untuk semua akun</p>
                </div>
                <button @click="closeModal()" class="p-2 rounded-lg hover:bg-slate-100 transition text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Jenis <span class="text-rose-500">*</span></label>
                    <input type="text" x-model="form.name" placeholder="Contoh: Listrik, Internet, Pengisian E-Wallet"
                           :class="errors.name?'border-rose-400 ring-1 ring-rose-400':'border-slate-200'"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition">
                    <p x-show="errors.name" x-text="errors.name" class="text-rose-500 text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <input type="text" x-model="form.description" placeholder="Keterangan tambahan (opsional)"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition">
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                <button @click="closeModal()" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                <button @click="save()" :disabled="saving" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-rose-600 hover:bg-rose-700 text-white transition disabled:opacity-50">
                    <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    <span x-text="saving?'Menyimpan...':(editItem?'Simpan Perubahan':'Tambah')"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal=false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Jenis Ini?</h3>
            <p class="text-sm text-slate-500 mb-5">Jenis <strong class="text-slate-700" x-text="deleteTarget?.name"></strong> akan dihapus permanen.</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal=false" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">Batal</button>
                <button @click="confirmDelete()" :disabled="deleting" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold bg-rose-600 hover:bg-rose-700 text-white transition disabled:opacity-50">
                    <span x-text="deleting?'Menghapus...':'Ya, Hapus'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function typeApp(apiUrl) {
    return {
        items:[],loading:true,saving:false,deleting:false,
        showModal:false,showDeleteModal:false,editItem:null,deleteTarget:null,
        form:{name:'',description:''},errors:{},
        csrf:document.querySelector('meta[name="csrf-token"]').content,
        async load(){this.loading=true;const res=await fetch(apiUrl,{headers:{'X-CSRF-TOKEN':this.csrf}});const data=await res.json();this.items=data.data;this.loading=false;},
        openModal(item=null){this.editItem=item;this.form=item?{name:item.name,description:item.description||''}:{name:'',description:''};this.errors={};this.showModal=true;},
        closeModal(){this.showModal=false;this.editItem=null;},
        async save(){this.saving=true;this.errors={};const url=this.editItem?`${apiUrl}/${this.editItem.id}`:apiUrl;const res=await fetch(url,{method:this.editItem?'PUT':'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':this.csrf},body:JSON.stringify(this.form)});const data=await res.json();if(!res.ok){this.errors=data.errors||{};}else{this.closeModal();await this.load();this.toast(data.message);}this.saving=false;},
        del(item){this.deleteTarget=item;this.showDeleteModal=true;},
        async confirmDelete(){this.deleting=true;const res=await fetch(`${apiUrl}/${this.deleteTarget.id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':this.csrf}});const data=await res.json();this.showDeleteModal=false;this.deleteTarget=null;await this.load();this.toast(data.message);this.deleting=false;},
        toast(msg){window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:msg}}));},
    }
}
</script>
@endpush