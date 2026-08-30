<div class="fixed top-6 inset-x-0 z-[100] px-4 lg:px-12 pointer-events-none">
  <nav class="max-w-7xl mx-auto bg-white/80 backdrop-blur-2xl border border-white/40 px-6 py-3 flex items-center justify-between rounded-full shadow-[0_20px_50px_rgba(0,0,0,0.06)] pointer-events-auto transition-all duration-700 hover:bg-white hover:border-slate-200">
    
    <!-- Logo -->
    <a href="{{ route('home') }}" class="flex items-center group cursor-pointer">
      <div class="w-9 h-9 bg-slate-900 rounded-full flex items-center justify-center mr-3 shadow-md group-hover:rotate-[360deg] transition-transform duration-1000">
        <span class="text-gold font-serif text-lg font-bold">E</span>
      </div>
      <h1 class="text-lg font-medium tracking-[0.15em] text-slate-900 uppercase">
        Elite<span class="font-light text-slate-400 italic lowercase tracking-normal ml-1">Archive</span>
      </h1>
    </a>

    <!-- Center Navigation Links -->
    <ul class="hidden lg:flex items-center space-x-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">
      <li>
        <a href="{{ route('home') }}" class="relative px-5 py-2 transition-all hover:text-slate-900 {{ request()->routeIs('home') ? 'text-slate-900 font-black' : '' }} group">
          Archive
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold {{ request()->routeIs('home') ? 'w-4' : '' }} group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
      <li>
        <a href="{{ route('service') }}" class="relative px-5 py-2 transition-all hover:text-slate-900 {{ request()->routeIs('service') ? 'text-slate-900 font-black' : '' }} group">
          Services
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold {{ request()->routeIs('service') ? 'w-4' : '' }} group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
      <li>
        <a href="{{ route('about') }}" class="relative px-5 py-2 transition-all hover:text-slate-900 {{ request()->routeIs('about') ? 'text-slate-900 font-black' : '' }} group">
          Philosophy
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold {{ request()->routeIs('about') ? 'w-4' : '' }} group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
      <li>
        <a href="{{ route('contact') }}" class="relative px-5 py-2 transition-all hover:text-slate-900 {{ request()->routeIs('contact') ? 'text-slate-900 font-black' : '' }} group">
          Contact
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold {{ request()->routeIs('contact') ? 'w-4' : '' }} group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
    </ul>

    <!-- Right Controls: Wishlist, Cart & Auth -->
    <div class="flex items-center space-x-3 sm:space-x-4">
      
      <!-- Wishlist link -->
      <a href="{{ route('wishlist.index') }}" 
         class="relative p-2.5 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition"
         title="Saved Archive">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        @auth
          @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
          @if($wishlistCount > 0)
            <span class="absolute -top-1 -right-1 bg-gold text-slate-950 text-[10px] font-black rounded-full w-4 h-4 flex items-center justify-center shadow-sm">
              {{ $wishlistCount }}
            </span>
          @endif
        @endauth
      </a>

      <!-- Cart link -->
      <a href="{{ route('cart.index') }}" 
         class="relative p-2.5 rounded-full text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition"
         title="Collection Bag">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        @php $cartCount = array_sum(array_column(session('cart') ?: [], 'quantity')); @endphp
        @if($cartCount > 0)
          <span class="absolute -top-1 -right-1 bg-slate-900 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center shadow-sm">
            {{ $cartCount }}
          </span>
        @endif
      </a>

      <!-- Account / Auth -->
      @auth
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
          <button @click="open = !open" 
                  class="flex items-center gap-2 pl-3 pr-4 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-900 transition text-xs font-bold">
            <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-[10px] font-black flex items-center justify-center uppercase">
              {{ substr(auth()->user()->name, 0, 1) }}
            </span>
            <span class="hidden md:inline truncate max-w-[100px]">{{ auth()->user()->name }}</span>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>

          <!-- Dropdown menu -->
          <div x-show="open" 
               x-transition:enter="transition ease-out duration-100"
               x-transition:enter-start="transform opacity-0 scale-95"
               x-transition:enter-end="transform opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-75"
               x-transition:leave-start="transform opacity-100 scale-100"
               x-transition:leave-end="transform opacity-0 scale-95"
               class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 z-50 text-xs font-semibold"
               style="display: none;">
            
            <div class="px-4 py-2 border-b border-slate-100">
              <p class="font-bold text-slate-900">{{ auth()->user()->name }}</p>
              <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>

            <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
              My Orders
            </a>

            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
              My Wishlist
            </a>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Profile Settings
            </a>

            @if(auth()->user()->seller)
              <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-amber-700 hover:bg-amber-50 transition font-bold border-t border-slate-100">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Seller Studio
              </a>
            @else
              <a href="{{ route('seller.register') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-600 hover:bg-slate-50 transition border-t border-slate-100">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Become a Seller
              </a>
            @endif

            @if(auth()->user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-slate-900 hover:bg-slate-50 transition font-bold border-t border-slate-100">
                <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Admin Curator Studio
              </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
              @csrf
              <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-red-600 hover:bg-red-50 transition">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
              </button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="hidden sm:block text-[10px] font-black uppercase tracking-[0.25em] text-slate-600 hover:text-slate-900 transition-colors">
          Login
        </a>
        
        <a href="{{ route('register') }}" 
           class="relative group overflow-hidden px-6 py-2.5 bg-[#141414] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full transition-all shadow-sm hover:bg-slate-800">
          <span>Join</span>
        </a>
      @endauth
    </div>

  </nav>
</div>