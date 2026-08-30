<x-layout.admin-layout>
  <div class="space-y-8">
    
    <!-- Hero Greeting -->
    <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-widest text-accent">Curator Workspace</p>
          <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Executive Dashboard</h2>
          <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
            Monitor acquisitions, gross store revenue, customer transactions, and catalog inventory health.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <a href="{{ route('admin.books.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-accent shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Add Masterpiece
          </a>
        </div>
      </div>
    </section>

    <!-- Key Metrics 4-Up Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Revenue</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950 font-serif">${{ number_format($totalRevenue, 2) }}</h3>
          <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Settled</span>
        </div>
      </div>

      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Dispatches</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950">{{ number_format($totalOrders) }}</h3>
          <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">Orders</span>
        </div>
      </div>

      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Active Patrons</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950">{{ number_format($totalCustomers) }}</h3>
          <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">Readers</span>
        </div>
      </div>

      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Archive Titles</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950">{{ number_format($totalBooks) }}</h3>
          <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">${{ number_format($averagePrice, 2) }} avg</span>
        </div>
      </div>
    </div>

    <!-- Split Grid: Recent Orders & Recent Books -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
      
      <!-- Recent Orders Column -->
      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-7 space-y-6">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
          <div>
            <h3 class="text-base font-black text-slate-950">Recent Acquisition Dispatches</h3>
            <p class="text-xs text-slate-400 mt-0.5">Latest patron purchases awaiting or in fulfillment.</p>
          </div>
          <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">View Reports →</a>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse($recentOrders as $order)
            <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4 text-xs">
              <div class="min-w-0">
                <p class="font-mono font-bold text-slate-900">{{ $order->order_number }}</p>
                <p class="text-slate-500 mt-0.5">{{ $order->shipping_name }} · {{ $order->items->count() }} item(s)</p>
              </div>

              <div class="flex items-center gap-4 flex-shrink-0">
                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $order->status_color }}">
                  {{ $order->status }}
                </span>
                <span class="font-serif font-bold text-slate-900 text-sm">${{ number_format($order->total, 2) }}</span>
              </div>
            </div>
          @empty
            <p class="py-8 text-center text-xs text-slate-400">No orders received yet.</p>
          @endforelse
        </div>
      </section>

      <!-- Recent Added Books Column -->
      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-5 space-y-6">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
          <div>
            <h3 class="text-base font-black text-slate-950">Latest Additions</h3>
            <p class="text-xs text-slate-400 mt-0.5">Recently curated titles.</p>
          </div>
          <a href="{{ route('admin.books.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">View All →</a>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse($recentBooks as $book)
            <a href="{{ route('admin.books.show', $book->slug) }}" class="flex items-center gap-3.5 py-3 first:pt-0 last:pb-0 transition hover:bg-slate-50 rounded-xl px-2">
              <img src="{{ $book->image_url ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=160' }}"
                   alt="{{ $book->title }}"
                   class="h-12 w-9 rounded-lg object-cover shadow-sm flex-shrink-0">
              <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-slate-900">{{ $book->title }}</p>
                <p class="text-[10px] text-slate-400">${{ number_format($book->price, 2) }} · {{ $book->category ?? 'General' }}</p>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          @empty
            <p class="py-8 text-center text-xs text-slate-400">No books added yet.</p>
          @endforelse
        </div>
      </section>

    </div>
  </div>
</x-layout.admin-layout>
