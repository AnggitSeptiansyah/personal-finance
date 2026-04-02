{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div x-data="dashboardApp()" x-init="loadSummary()">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- Total Balance --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white col-span-1 md:col-span-3">
            <p class="text-emerald-100 text-sm font-medium mb-1">Total Kekayaan</p>
            <p class="text-3xl font-bold" x-text="formatRupiah(summary.total_balance)">Rp 0</p>
            <p class="text-emerald-200 text-xs mt-2">Cash + Saldo Bank</p>
        </div>

        {{-- Cash Balance --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm font-medium">Saldo Cash</p>
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-xl">💵</div>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="formatRupiah(summary.cash?.balance)">Rp 0</p>
            <div class="mt-4 flex justify-between text-xs">
                <div>
                    <p class="text-gray-400">Masuk Bulan Ini</p>
                    <p class="text-green-600 font-semibold" x-text="formatRupiah(summary.cash?.month_income)">Rp 0</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400">Keluar Bulan Ini</p>
                    <p class="text-red-500 font-semibold" x-text="formatRupiah(summary.cash?.month_expense)">Rp 0</p>
                </div>
            </div>
        </div>

        {{-- Bank Balance --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-500 text-sm font-medium">Saldo Bank</p>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🏦</div>
            </div>
            <p class="text-2xl font-bold text-gray-800" x-text="formatRupiah(summary.bank?.balance)">Rp 0</p>
            <div class="mt-4 flex justify-between text-xs">
                <div>
                    <p class="text-gray-400">Masuk Bulan Ini</p>
                    <p class="text-green-600 font-semibold" x-text="formatRupiah(summary.bank?.month_income)">Rp 0</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-400">Keluar Bulan Ini</p>
                    <p class="text-red-500 font-semibold" x-text="formatRupiah(summary.bank?.month_expense)">Rp 0</p>
                </div>
            </div>
        </div>

        {{-- Quick Nav --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm font-medium mb-4">Akses Cepat</p>
            <div class="space-y-2">
                <a href="{{ route('cash.incomes') }}"
                   class="flex items-center p-2 rounded-lg hover:bg-green-50 text-sm text-gray-700 transition">
                    <span class="w-6 h-6 bg-green-100 rounded text-xs flex items-center justify-center mr-2">+</span>
                    Tambah Pemasukan Cash
                </a>
                <a href="{{ route('cash.expenses') }}"
                   class="flex items-center p-2 rounded-lg hover:bg-red-50 text-sm text-gray-700 transition">
                    <span class="w-6 h-6 bg-red-100 rounded text-xs flex items-center justify-center mr-2">-</span>
                    Tambah Pengeluaran Cash
                </a>
                <a href="{{ route('bank.incomes') }}"
                   class="flex items-center p-2 rounded-lg hover:bg-blue-50 text-sm text-gray-700 transition">
                    <span class="w-6 h-6 bg-blue-100 rounded text-xs flex items-center justify-center mr-2">+</span>
                    Tambah Pemasukan Bank
                </a>
                <a href="{{ route('bank.expenses') }}"
                   class="flex items-center p-2 rounded-lg hover:bg-orange-50 text-sm text-gray-700 transition">
                    <span class="w-6 h-6 bg-orange-100 rounded text-xs flex items-center justify-center mr-2">-</span>
                    Tambah Pengeluaran Bank
                </a>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function dashboardApp() {
    return {
        summary: { total_balance: 0, cash: {}, bank: {} },

        async loadSummary() {
            try {
                const res = await fetch('/api/dashboard/summary', {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                this.summary = data;
            } catch(e) { console.error(e); }
        },

        formatRupiah(value) {
            if (!value && value !== 0) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            }).format(value);
        }
    }
}
</script>
@endpush