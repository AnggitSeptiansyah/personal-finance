<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FinansialKu — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;}[x-cloak]{display:none!important;}.scrollbar-hide::-webkit-scrollbar{display:none;}.scrollbar-hide{-ms-overflow-style:none;scrollbar-width:none;}</style>
</head>
<body class="bg-slate-50 antialiased">
<div x-data="{ open: window.innerWidth >= 1024 }" class="flex h-screen overflow-hidden">
    <aside :class="open ? 'w-64' : 'w-0 lg:w-16'" class="bg-slate-900 text-white transition-all duration-300 flex flex-col shrink-0 overflow-hidden z-30">
        <div class="flex items-center justify-between px-4 py-5 border-b border-slate-700/50">
            <div x-show="open" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold">F</div>
                <span class="text-base font-bold tracking-tight">FinansialKu</span>
            </div>
            <div x-show="!open" class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold mx-auto">F</div>
            <button @click="open=!open" x-show="open" class="p-1.5 rounded-lg hover:bg-slate-700 transition text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto scrollbar-hide py-3 px-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-500 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/></svg>
                <span x-show="open" class="text-sm font-semibold">Dashboard</span>
            </a>
            <p x-show="open" class="text-xs font-semibold text-slate-500 uppercase tracking-widest px-3 mt-5 mb-2">💵 Cash</p>
            @foreach([['cash.income-types','Jenis Pemasukan'],['cash.incomes','Pemasukan'],['cash.expense-types','Jenis Pengeluaran'],['cash.expenses','Pengeluaran']] as [$r,$l])
            <a href="{{ route($r) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium transition-all {{ request()->routeIs($r) ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                <span x-show="open">{{ $l }}</span>
            </a>
            @endforeach
            <p x-show="open" class="text-xs font-semibold text-slate-500 uppercase tracking-widest px-3 mt-5 mb-2">🏦 Bank</p>
            @foreach([['bank.accounts','Akun Bank'],['bank.income-types','Jenis Pemasukan'],['bank.incomes','Pemasukan'],['bank.expense-types','Jenis Pengeluaran'],['bank.expenses','Pengeluaran']] as [$r,$l])
            <a href="{{ route($r) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl mb-0.5 text-sm font-medium transition-all {{ request()->routeIs($r) ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                <span x-show="open">{{ $l }}</span>
            </a>
            @endforeach
        </nav>
        <div class="px-2 py-3 border-t border-slate-700/50">
            <div x-show="open" class="flex items-center gap-3 px-3 py-2 rounded-xl bg-slate-800 mb-2">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-sm font-bold shrink-0">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div class="min-w-0"><p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p><p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p></div>
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-rose-400 transition text-sm font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="open">Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <button @click="open=!open" class="p-2 rounded-lg hover:bg-slate-100 transition text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div><h1 class="text-lg font-bold text-slate-800 leading-tight">@yield('title','Dashboard')</h1><p class="text-xs text-slate-400">@yield('subtitle','&nbsp;')</p></div>
            </div>
            <span class="text-sm text-slate-400 font-medium hidden md:block">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
        </header>
        <div x-data x-on:show-toast.window="
            let t=$el.querySelector('#toast');
            t.querySelector('span').textContent=$event.detail.message;
            t.classList.remove('opacity-0','translate-y-2');t.classList.add('opacity-100','translate-y-0');
            setTimeout(()=>{t.classList.add('opacity-0','translate-y-2');t.classList.remove('opacity-100','translate-y-0');},3000)">
            <div id="toast" class="fixed top-5 right-5 z-50 bg-slate-800 text-white text-sm px-4 py-3 rounded-xl shadow-xl flex items-center gap-2 transition-all duration-300 opacity-0 translate-y-2">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span></span>
            </div>
        </div>
        <main class="flex-1 overflow-y-auto p-6">@yield('content')</main>
    </div>
</div>

{{-- ✅ LETAKKAN DI SINI — sebelum @stack('scripts') --}}
<script>
let sanctumInitialized = false;

window.apiFetch = async function(url, options = {}) {
    if (!sanctumInitialized && ['POST','PUT','PATCH','DELETE'].includes((options.method || 'GET').toUpperCase())) {
        await fetch('/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'include',
        });
        sanctumInitialized = true;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    return fetch(url, {
        ...options,
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
            ...(options.headers || {})
        }
    });
};

window.rp = (v) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', minimumFractionDigits: 0
}).format(v || 0);

window.fmtDate = (d) => {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    });
};
</script>

@stack('scripts')
</body>
</html>