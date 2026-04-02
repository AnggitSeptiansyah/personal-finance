{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FinansialKu - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">

    {{-- Sidebar --}}
    <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

        {{-- Sidebar Navigation --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
               class="bg-emerald-800 text-white transition-all duration-300 flex flex-col shrink-0">

            {{-- Logo --}}
            <div class="flex items-center justify-between p-4 border-b border-emerald-700">
                <span x-show="sidebarOpen" class="text-xl font-bold text-emerald-100">💰 FinansialKu</span>
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-1 rounded hover:bg-emerald-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Nav Menu --}}
            <nav class="flex-1 overflow-y-auto py-4">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-4 py-3 hover:bg-emerald-700 transition
                          {{ request()->routeIs('dashboard') ? 'bg-emerald-700 border-r-4 border-emerald-300' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen" class="ml-3 text-sm font-medium">Dashboard</span>
                </a>

                {{-- CASH SECTION --}}
                <div x-data="{ cashOpen: {{ request()->is('cash/*') ? 'true' : 'false' }} }">
                    <button @click="cashOpen = !cashOpen"
                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-emerald-700 transition text-emerald-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium">Cash</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="cashOpen ? 'rotate-180' : ''"
                             class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="cashOpen && sidebarOpen" class="bg-emerald-900">
                        <a href="{{ route('cash.income-types') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('cash.income-types') ? 'text-white font-medium' : '' }}">
                            Jenis Pemasukan
                        </a>
                        <a href="{{ route('cash.incomes') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('cash.incomes') ? 'text-white font-medium' : '' }}">
                            Pemasukan
                        </a>
                        <a href="{{ route('cash.expense-types') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('cash.expense-types') ? 'text-white font-medium' : '' }}">
                            Jenis Pengeluaran
                        </a>
                        <a href="{{ route('cash.expenses') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('cash.expenses') ? 'text-white font-medium' : '' }}">
                            Pengeluaran
                        </a>
                    </div>
                </div>

                {{-- BANK SECTION --}}
                <div x-data="{ bankOpen: {{ request()->is('bank/*') ? 'true' : 'false' }} }">
                    <button @click="bankOpen = !bankOpen"
                            class="w-full flex items-center justify-between px-4 py-3 hover:bg-emerald-700 transition text-emerald-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span x-show="sidebarOpen" class="ml-3 text-sm font-medium">Bank</span>
                        </div>
                        <svg x-show="sidebarOpen" :class="bankOpen ? 'rotate-180' : ''"
                             class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="bankOpen && sidebarOpen" class="bg-emerald-900">
                        <a href="{{ route('bank.income-types') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('bank.income-types') ? 'text-white font-medium' : '' }}">
                            Jenis Pemasukan
                        </a>
                        <a href="{{ route('bank.incomes') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('bank.incomes') ? 'text-white font-medium' : '' }}">
                            Pemasukan
                        </a>
                        <a href="{{ route('bank.expense-types') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('bank.expense-types') ? 'text-white font-medium' : '' }}">
                            Jenis Pengeluaran
                        </a>
                        <a href="{{ route('bank.expenses') }}"
                           class="flex items-center pl-12 pr-4 py-2 text-sm text-emerald-200 hover:bg-emerald-700 transition
                                  {{ request()->routeIs('bank.expenses') ? 'text-white font-medium' : '' }}">
                            Pengeluaran
                        </a>
                    </div>
                </div>

            </nav>

            {{-- User Info & Logout --}}
            <div class="p-4 border-t border-emerald-700">
                <div x-show="sidebarOpen" class="text-xs text-emerald-300 mb-2 truncate">
                    {{ auth()->user()->name }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center text-emerald-200 hover:text-white text-sm transition">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-2">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                <div class="text-sm text-gray-500">{{ now()->format('d F Y') }}</div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="mx-6 mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
            @endif

            <div class="p-6">
                @yield('content')
            </div>
        </main>

    </div>

    @stack('scripts')
</body>
</html>