<header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-4 flex items-center justify-between">
  <div class="flex items-center gap-10">
    <label for="mobile-sidebar-toggle" class="md:hidden p-2.5 rounded-xl text-slate-600 hover:bg-slate-50 cursor-pointer transition-all duration-500 ease-out">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </label>

    <div class="hidden md:block">
        <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 mb-0.5">Administrative</h2>
        <p class="text-xl font-serif italic text-slate-900">Curator <span class="font-sans not-italic font-black border-b-2 border-accent/20">Studio</span></p>
    </div>

    <div class="hidden lg:block relative group">
      <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 group-focus-within:text-accent transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      <input type="search" 
             placeholder="Find in collection..." 
             class="w-72 pl-12 pr-4 py-2.5 rounded-2xl bg-slate-50 border border-transparent focus:bg-white focus:border-accent/20 focus:outline-none focus:ring-4 focus:ring-accent/5 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] text-xs font-bold tracking-wide">
    </div>
  </div>

  <div class="flex items-center gap-6">
    <button class="hidden md:flex items-center gap-2 bg-slate-900 text-white px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-accent hover:shadow-2xl hover:shadow-accent/20 hover:-translate-y-0.5 active:scale-95 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
      <span class="text-lg leading-none">+</span> Add Book
    </button>

    <button class="relative p-2.5 rounded-xl text-slate-400 hover:bg-slate-50 hover:text-slate-900 transition-all duration-500 ease-out group">
      <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-accent rounded-full border-2 border-white animate-pulse"></span>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
    </button>

    <div class="h-8 w-[1px] bg-slate-100 hidden md:block"></div>

    <div class="flex items-center gap-5">
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-slate-300 hover:text-red-500 transition-colors duration-500">
                Exit
            </button>
        </form>
        
        <div class="group relative">
            <div class="h-10 w-10 rounded-2xl bg-slate-900 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-slate-200 cursor-pointer group-hover:rotate-6 transition-transform duration-500 ease-out">
                A
            </div>
            <div class="absolute -inset-1 border border-accent/20 rounded-[1.25rem] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
    </div>
  </div>
</header>