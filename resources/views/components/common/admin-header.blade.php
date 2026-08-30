<header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
  <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4 min-w-0">
      <label for="mobile-sidebar-toggle" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 cursor-pointer transition" title="Open menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </label>

      <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Administrative</p>
        <h1 class="truncate text-lg font-black text-slate-950">Curator Studio</h1>
      </div>
    </div>

    <div class="hidden lg:block flex-1 max-w-md">
      <div class="relative">
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="search"
               placeholder="Search collection"
               class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-3 text-sm font-medium text-slate-700 outline-none transition focus:border-accent/60 focus:bg-white focus:ring-4 focus:ring-accent/10">
      </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">


      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="rounded-lg px-3 py-2 text-sm font-bold text-slate-500 transition hover:bg-red-50 hover:text-red-600">
          Exit
        </button>
      </form>

      <div class="h-9 w-9 rounded-lg bg-slate-900 flex items-center justify-center text-xs font-black text-white">
        A
      </div>
    </div>
  </div>
</header>
