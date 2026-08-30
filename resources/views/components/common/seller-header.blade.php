<header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
  <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4 min-w-0">
      <div class="min-w-0">
        <p class="text-[10px] font-bold uppercase tracking-widest text-amber-600">Merchant Portal</p>
        <h1 class="truncate text-base font-black text-slate-900">{{ auth()->user()->seller->store_name ?? 'Seller Studio' }}</h1>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <a href="{{ route('home') }}" target="_blank"
         class="text-xs font-bold text-slate-500 hover:text-slate-900 transition flex items-center gap-1.5">
        <span>Storefront ↗</span>
      </a>

      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="rounded-full px-3 py-1.5 text-xs font-bold text-slate-500 transition hover:bg-red-50 hover:text-red-600">
          Exit
        </button>
      </form>

      <div class="h-8 w-8 rounded-full bg-slate-900 flex items-center justify-center text-xs font-bold text-white uppercase">
        {{ substr(auth()->user()->name, 0, 1) }}
      </div>
    </div>
  </div>
</header>
