@extends('layouts.app')
@section('title', 'Akun Bank')
@section('subtitle', 'Kelola semua rekening bank Anda')

@section('content')
<div x-data="bankAccountsApp()" x-init="load()">

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-slate-500">Total <span class="font-bold text-slate-700" x-text="accounts.length"></span> akun bank terdaftar</p>
        <button @click="openModal()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Akun Bank
        </button>
    </div>

    {{-- Cards Grid --}}
    <div x-show="loading" class="flex items-center justify-center py-16 text-slate-400">
        <svg class="w-8 h-8 animate-spin text-slate-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
    </div>

    <div x-show="!loading && accounts.length===0" class="flex flex-col items-center py-16 text-slate-400 bg-white rounded-2xl border border-slate-200">
        <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        <p class="text-sm font-medium">Belum ada akun bank</p>
        <p class="text-xs mt-1">Tambahkan rekening BCA, BRI, BNI, Mandiri, dll</p>
    </div>

    <div x-show="!loading && accounts.length>0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <template x-for="acc in accounts" :key="acc.id">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden group">
                {{-- Card Header --}}
                <div class="p-5 pb-4">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center font-bold text-white text-sm shadow-lg shadow-blue-500/20"
                                 x-text="acc.bank_name.slice(0,3).toUpperCase()"></div>
                            <div>
                                <p class="font-bold text-slate-800" x-text="acc.bank_name"></p>
                                <p class="text-xs text-slate-400" x-text="acc.account_name||'—'"></p>
                            </div>
                        </div>
                        <div class="flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="openModal(acc)" class="p-1.5 rounded-lg hover:bg-blue-50 text-slate-400 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button @click="del(acc)" class="p-1.5 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    <div x-show="acc.account_number" class="text-xs text-slate-400 font-mono mb-1" x-text="'No. Rek: '+acc.account_number"></div>
                    <div x-show="acc.description" class="text-xs text-slate-400 mb-3" x-text="acc.description"></div>
                </div>
                {{-- Balance --}}
                <div class="mx-5 mb-5 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-1">Saldo Saat Ini</p>
                    <p class="text-2xl font-bold text-emerald-700" x-text="rp(acc.balance)"></p>
                </div>
            </div>
        </template>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-800" x-text="editItem?'Edit Akun Bank':'Tambah Akun Bank'"></h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="editItem?'Ubah informasi rekening bank':'Daftarkan rekening bank baru'"></p>
                </div>
                <button @click="closeModal()" class="p-2 rounded-lg hover:bg-slate-100 transition text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Bank <span class="text-rose-500">*</span></label>
                    <input type="text" x-model="form.bank_name" placeholder="Contoh: BCA, BRI, BNI, Mandiri"
                           :class="errors.bank_name?'border-rose-400 ring-1 ring-rose-400':'border-slate-200'"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <p x-show="errors.bank_name" x-text="errors.bank_name" class="text-rose-500 text-xs mt-1"></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pemilik Rekening</label>
                    <input type="text" x-model="form.account_name" placeholder="Nama sesuai buku tabungan"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Rekening</label>
                    <input type="text" x-model="form.account_number" placeholder="Opsional"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <input type="text" x-model="form.description" placeholder="Contoh: Tabungan utama, Dana darurat"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                <button @click="closeModal()" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                <button @click="save()" :disabled="saving" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white transition disabled:opacity-50">
                    <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    <span x-text="saving?'Menyimpan...':(editItem?'Simpan Perubahan':'Tambah Akun')"></span>
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
                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Akun Bank?</h3>
            <p class="text-sm text-slate-500 mb-2">Akun <strong class="text-slate-700" x-text="deleteTarget?.bank_name"></strong> akan dihapus.</p>
            <p class="text-xs text-rose-600 bg-rose-50 rounded-lg p-2 mb-5">⚠ Semua transaksi pemasukan dan pengeluaran pada akun ini juga akan terhapus permanen!</p>
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
function bankAccountsApp() {
    return {
        accounts:[],loading:true,saving:false,deleting:false,
        showModal:false,showDeleteModal:false,editItem:null,deleteTarget:null,
        form:{bank_name:'',account_name:'',account_number:'',description:''},errors:{},
        csrf:document.querySelector('meta[name="csrf-token"]').content,
        async load(){
            this.loading=true;
            const res=await fetch('/api/bank/accounts',{headers:{'X-CSRF-TOKEN':this.csrf}});
            const data=await res.json();this.accounts=data.data;this.loading=false;
        },
        openModal(item=null){
            this.editItem=item;
            this.form=item?{bank_name:item.bank_name,account_name:item.account_name||'',account_number:item.account_number||'',description:item.description||''}:{bank_name:'',account_name:'',account_number:'',description:''};
            this.errors={};this.showModal=true;
        },
        closeModal(){this.showModal=false;this.editItem=null;},
        async save(){
            this.saving=true;this.errors={};
            const url=this.editItem?`/api/bank/accounts/${this.editItem.id}`:'/api/bank/accounts';
            const res=await fetch(url,{method:this.editItem?'PUT':'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':this.csrf},body:JSON.stringify(this.form)});
            const data=await res.json();
            if(!res.ok){if(data.errors)this.errors=data.errors;}
            else{this.closeModal();await this.load();this.toast(data.message);}
            this.saving=false;
        },
        del(item){this.deleteTarget=item;this.showDeleteModal=true;},
        async confirmDelete(){
            this.deleting=true;
            const res=await fetch(`/api/bank/accounts/${this.deleteTarget.id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':this.csrf}});
            const data=await res.json();this.showDeleteModal=false;this.deleteTarget=null;
            await this.load();this.toast(data.message);this.deleting=false;
        },
        toast(msg){window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:msg}}));},
        rp(v){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v||0);},
    }
}
</script>
@endpush