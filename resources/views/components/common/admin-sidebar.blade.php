@php
  $links = [
    [
      'label' => 'Dashboard',
      'route' => route('admin.dashboard'),
      'active' => request()->routeIs('admin.dashboard'),
      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
    ],
    [
      'label' => 'Collection Archive',
      'route' => route('admin.books.index'),
      'active' => request()->routeIs('admin.books.*'),
      'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m0 0v2'
    ],
    [
      'label' => 'Customers',
      'route' => route('admin.customers.index'),
      'active' => request()->routeIs('admin.customers.*'),
      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
    ],
    [
      'label' => 'Orders',
      'route' => route('admin.orders.index'),
      'active' => request()->routeIs('admin.orders.*'),
      'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'
    ],
    [
      'label' => 'Merchants',
      'route' => route('admin.sellers.index'),
      'active' => request()->routeIs('admin.sellers.*'),
      'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
    ],
    [
      'label' => 'Reviews',
      'route' => route('admin.reviews.index'),
      'active' => request()->routeIs('admin.reviews.*'),
      'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'
    ],
    [
      'label' => 'Reports & Analytics',
      'route' => route('admin.reports.index'),
      'active' => request()->routeIs('admin.reports.*'),
      'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
    ],
  ];
@endphp

<aside id="dashboardSidebarMobile" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-900 text-white shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0">
    
    <!-- Header -->
    <div class="h-20 flex items-center px-6 flex-shrink-0 border-b border-slate-800">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-lg font-bold">
        <div class="w-9 h-9 rounded-full bg-white text-slate-900 font-serif font-black flex items-center justify-center shadow-md">
          E
        </div>
        <span class="tracking-wider uppercase text-sm">Elite<span class="text-amber-400 font-normal italic lowercase ml-1">Studio</span></span>
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
        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-3 rounded-full px-4 py-2.5 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition">
          <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          <span>View Public Store ↗</span>
        </a>
      </div>
    </nav>

    <!-- User Profile Footer -->
    <div class="p-4 flex-shrink-0 border-t border-slate-800 bg-slate-950">
      <div class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-900 border border-slate-800">
        <div class="h-9 w-9 rounded-full flex items-center justify-center text-xs font-bold text-slate-900 bg-amber-400">
          AD
        </div>
        <div class="overflow-hidden min-w-0">
          <p class="text-xs font-bold truncate text-white">{{ auth()->user()->name ?? 'Administrator' }}</p>
          <p class="text-[9px] font-bold uppercase tracking-widest text-amber-400">Curator Admin</p>
        </div>
      </div>
    </div>
</aside>