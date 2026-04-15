@extends('layouts.app')
@section('title', 'Pemasukan Bank')
@section('subtitle', 'Catat dan kelola semua pemasukan saldo rekening bank Anda')

@section('content')
<div x-data="bankIncomesApp()" x-init="load()">

    {{-- Filter per Akun Bank --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mr-1">Filter Bank:</span>
            <button @click="filterAccount=null; load()"
                    :class="filterAccount===null?'bg-slate-800 text-white':'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition">Semua Bank</button>
            <template x-for="acc in accounts" :key="acc.id">
                <button @click="filterAccount=acc.id; load()"
                        :class="filterAccount===acc.id?'bg-blue-600 text-white':'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                        x-text="acc.bank_name"></button>
            </template>
            <div x-show="accounts.length===0" class="text-xs text-amber-600">
                ⚠ Belum ada akun bank. <a href="{{ route('bank.accounts') }}" class="underline font-semibold">Tambahkan dulu</a>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-slate-500">Menampilkan <span class="font-semibold text-slate-700" x-text="items.length"></span> transaksi</p>
        <button @click="openModal()" :disabled="accounts.length===0"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Pemasukan
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div x-show="loading" class="flex items-center justify-center py-16">
            <svg class="w-8 h-8 animate-spin text-slate-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
        </div>
        <div x-show="!loading && items.length===0" class="flex flex-col items-center py-16 text-slate-400">
            <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            <p class="text-sm font-medium">Belum ada pemasukan bank</p>
            <p class="text-xs mt-1">Klik Tambah Pemasukan untuk mencatat</p>
        </div>
        <div x-show="!loading && items.length>0" class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Akun Bank</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="item in items" :key="item.id">
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap" x-text="fmtDate(item.date)"></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">
                                    <span class="w-4 h-4 bg-blue-600 rounded text-white text-xs flex items-center justify-center font-bold leading-none"
                                          x-text="(item.bank_account?.bank_name||'?').slice(0,1)"></span>
                                    <span x-text="item.bank_account?.bank_name||'—'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700"
                                      x-text="item.bank_income_type?.name||'—'"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-600" x-text="rp(item.amount)"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 max-w-xs truncate" x-text="item.note||'—'"></td>
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
        <div x-show="pagination.last_page>1" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-sm text-slate-500">Halaman <span class="font-semibold" x-text="pagination.current_page"></span> dari <span class="font-semibold" x-text="pagination.last_page"></span></p>
            <div class="flex gap-2">
                <button @click="load(pagination.current_page-1)" :disabled="pagination.current_page<=1" class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 disabled:opacity-40 transition">← Sebelumnya</button>
                <button @click="load(pagination.current_page+1)" :disabled="pagination.current_page>=pagination.last_page" class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 disabled:opacity-40 transition">Selanjutnya →</button>
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md"
             x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-800" x-text="editItem?'Edit Pemasukan Bank':'Tambah Pemasukan Bank'"></h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="editItem?'Ubah data pemasukan bank':'Pilih akun bank tujuan dan catat pemasukan'"></p>
                </div>
                <button @click="closeModal()" class="p-2 rounded-lg hover:bg-slate-100 transition text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="px-6 py-5 space-y-4">

                {{-- Akun Bank --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Akun Bank Tujuan <span class="text-rose-500">*</span></label>
                    <select x-model="form.bank_account_id"
                            :class="errors.bank_account_id?'border-rose-400 ring-1 ring-rose-400':'border-slate-200'"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white">
                        <option value="">— Pilih Akun Bank —</option>
                        <template x-for="acc in accounts" :key="acc.id">
                            <option :value="acc.id" x-text="`${acc.bank_name}${acc.account_name?' ('+acc.account_name+')':''}`"></option>
                        </template>
                    </select>
                    <p x-show="errors.bank_account_id" x-text="errors.bank_account_id" class="text-rose-500 text-xs mt-1"></p>
                </div>

                {{-- Jenis Pemasukan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Pemasukan <span class="text-rose-500">*</span></label>
                    <select x-model="form.bank_income_type_id"
                            :class="errors.bank_income_type_id?'border-rose-400 ring-1 ring-rose-400':'border-slate-200'"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition bg-white">
                        <option value="">— Pilih Jenis —</option>
                        <template x-for="type in types" :key="type.id">
                            <option :value="type.id" x-text="type.name"></option>
                        </template>
                    </select>
                    <p x-show="errors.bank_income_type_id" x-text="errors.bank_income_type_id" class="text-rose-500 text-xs mt-1"></p>
                    <p x-show="types.length===0" class="text-amber-600 text-xs mt-1">⚠ Belum ada jenis pemasukan bank. <a href="{{ route('bank.income-types') }}" class="underline">Tambahkan dulu</a></p>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">Rp</span>
                        <input type="number" x-model="form.amount" placeholder="0" min="1"
                               :class="errors.amount?'border-rose-400 ring-1 ring-rose-400':'border-slate-200'"
                               class="w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    </div>
                    <p x-show="errors.amount" x-text="errors.amount" class="text-rose-500 text-xs mt-1"></p>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" x-model="form.date"
                           :class="errors.date?'border-rose-400 ring-1 ring-rose-400':'border-slate-200'"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    <p x-show="errors.date" x-text="errors.date" class="text-rose-500 text-xs mt-1"></p>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan</label>
                    <input type="text" x-model="form.note" placeholder="Keterangan tambahan (opsional)"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                </div>

                {{-- Error API --}}
                <div x-show="apiError" class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700" x-text="apiError"></div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl">
                <button @click="closeModal()" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                <button @click="save()" :disabled="saving" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition disabled:opacity-50">
                    <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                    <span x-text="saving?'Menyimpan...':(editItem?'Simpan Perubahan':'Catat Pemasukan')"></span>
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
            <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Pemasukan Ini?</h3>
            <p class="text-sm text-slate-500 mb-1">Bank: <strong class="text-blue-600" x-text="deleteTarget?.bank_account?.bank_name"></strong></p>
            <p class="text-sm text-slate-500 mb-1">Nominal: <strong class="text-emerald-600" x-text="rp(deleteTarget?.amount)"></strong></p>
            <p class="text-sm text-slate-500 mb-5">Tanggal: <span x-text="fmtDate(deleteTarget?.date)"></span></p>
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
function bankIncomesApp() {
    return {
        items:[],accounts:[],types:[],
        loading:true,saving:false,deleting:false,
        showModal:false,showDeleteModal:false,
        editItem:null,deleteTarget:null,filterAccount:null,
        pagination:{current_page:1,last_page:1},apiError:'',
        form:{bank_account_id:'',bank_income_type_id:'',amount:'',date:today(),note:''},
        errors:{},
        csrf:document.querySelector('meta[name="csrf-token"]').content,

        async load(page=1) {
            this.loading=true;
            const params=new URLSearchParams({page});
            if(this.filterAccount)params.append('bank_account_id',this.filterAccount);

            const [iR,aR,tR]=await Promise.all([
                fetch(`/api/bank/incomes?${params}`,{headers:{'X-CSRF-TOKEN':this.csrf}}),
                fetch('/api/bank/accounts',{headers:{'X-CSRF-TOKEN':this.csrf}}),
                fetch('/api/bank/income-types',{headers:{'X-CSRF-TOKEN':this.csrf}}),
            ]);
            const [iD,aD,tD]=await Promise.all([iR.json(),aR.json(),tR.json()]);
            this.items=iD.data;
            this.pagination={current_page:iD.current_page,last_page:iD.last_page};
            this.accounts=aD.data;this.types=tD.data;this.loading=false;
        },

        openModal(item=null) {
            this.editItem=item;this.apiError='';
            this.form=item
                ?{bank_account_id:item.bank_account_id,bank_income_type_id:item.bank_income_type_id,amount:item.amount,date:item.date?.slice(0,10)||today(),note:item.note||''}
                :{bank_account_id:this.filterAccount||'',bank_income_type_id:'',amount:'',date:today(),note:''};
            this.errors={};this.showModal=true;
        },
        closeModal(){this.showModal=false;this.editItem=null;this.apiError='';},

        async save() {
            this.saving=true;this.errors={};this.apiError='';
            const url=this.editItem?`/api/bank/incomes/${this.editItem.id}`:'/api/bank/incomes';
            const res=await fetch(url,{
                method:this.editItem?'PUT':'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':this.csrf},
                body:JSON.stringify(this.form),
            });
            const data=await res.json();
            if(!res.ok){if(data.errors)this.errors=data.errors;if(data.message)this.apiError=data.message;}
            else{this.closeModal();await this.load(this.pagination.current_page);this.toast(data.message);}
            this.saving=false;
        },

        del(item){this.deleteTarget=item;this.showDeleteModal=true;},
        async confirmDelete(){
            this.deleting=true;
            const res=await fetch(`/api/bank/incomes/${this.deleteTarget.id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':this.csrf}});
            const data=await res.json();this.showDeleteModal=false;this.deleteTarget=null;
            await this.load(this.pagination.current_page);this.toast(data.message);this.deleting=false;
        },

        toast(msg){window.dispatchEvent(new CustomEvent('show-toast',{detail:{message:msg}}));},
        rp(v){return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',minimumFractionDigits:0}).format(v||0);},
        fmtDate(d){if(!d)return'—';return new Date(d).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});},
    }
}
function today(){return new Date().toISOString().slice(0,10);}
</script>
@endpush