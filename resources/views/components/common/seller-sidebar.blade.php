@php
  $links = [
    [
      'label' => 'Studio Overview',
      'route' => route('seller.dashboard'),
      'active' => request()->routeIs('seller.dashboard'),
      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
    ],
    [
      'label' => 'My Book Catalog',
      'route' => route('seller.books.index'),
      'active' => request()->routeIs('seller.books.*'),
      'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
    ],
    [
      'label' => 'Customer Orders',
      'route' => route('seller.orders.index'),
      'active' => request()->routeIs('seller.orders.*'),
      'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'
    ],
  ];
@endphp

<aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-900 text-white shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0">
    
    <!-- Header -->
    <div class="h-20 flex items-center px-6 flex-shrink-0 border-b border-slate-800">
      <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 text-lg font-bold">
        <div class="w-9 h-9 rounded-full bg-amber-400 text-slate-900 font-serif font-black flex items-center justify-center shadow-md">
          S
        </div>
        <span class="tracking-wider uppercase text-sm">Merchant<span class="text-amber-400 font-normal italic lowercase ml-1">Studio</span></span>
      </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
      @foreach($links as $link)
        <a href="{{ $link['route'] }}" 
           class="flex items-center gap-3 rounded-full px-4 py-3 text-xs font-bold uppercase tracking-wider transition-all duration-200 
           {{ $link['active'] ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
          
          <svg class="w-4 h-4 {{ $link['active'] ? 'text-slate-900' : 'text-slate-400' }}" 
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" />
          </svg>
          <span>{{ $link['label'] }}</span>
        </a>
      @endforeach

      <div class="pt-6 border-t border-slate-800 mt-6 space-y-1.5">
        <a href="{{ route('home') }}"
           class="flex items-center gap-3 rounded-full px-4 py-2.5 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition">
          <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          <span>Public Bookstore ↗</span>
        </a>
      </div>
    </nav>

    <!-- User Profile Footer -->
    <div class="p-4 flex-shrink-0 border-t border-slate-800 bg-slate-950">
      <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-900 border border-slate-800">
        <div class="h-9 w-9 rounded-full flex items-center justify-center text-xs font-bold text-slate-900 bg-amber-400 uppercase">
          {{ substr(auth()->user()->seller->store_name ?? 'S', 0, 2) }}
        </div>
        <div class="overflow-hidden min-w-0">
          <p class="text-xs font-bold truncate text-white">{{ auth()->user()->seller->store_name ?? 'Seller Store' }}</p>
          <p class="text-[9px] font-bold uppercase tracking-widest text-amber-400">Verified Merchant</p>
        </div>
      </div>
    </div>
</aside>
