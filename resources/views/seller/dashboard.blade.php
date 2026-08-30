<x-layout.seller-layout>
  <div class="space-y-8">
    
    <!-- Hero Banner -->
    <section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-700">Verified Merchant Atelier</p>
          </div>
          <h2 class="text-2xl font-black tracking-tight text-slate-950 sm:text-3xl font-serif">{{ $seller->store_name }}</h2>
          <p class="mt-1 max-w-2xl text-xs text-slate-500">
            {{ $seller->bio ?? 'Manage your specialized book catalog, monitor collector orders, and expand inventory.' }}
          </p>
        </div>

        <div class="flex items-center gap-3">
          <a href="{{ route('seller.books.create') }}" class="inline-flex items-center justify-center gap-2 rounded-full bg-[#141414] px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-800 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gold" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            List New Book
          </a>
        </div>
      </div>
    </section>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('status') }}
      </div>
    @endif

    <!-- Key Metrics 3-Up Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Merchant Sales</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950 font-serif">{{ config('ecommerce.currency_symbol') }}{{ number_format($revenue, 2) }}</h3>
          <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Active Orders</span>
        </div>
        @if($pendingPaymentRevenue > 0)
          <p class="mt-2 text-[10px] text-amber-600 font-bold">{{ config('ecommerce.currency_symbol') }}{{ number_format($pendingPaymentRevenue, 2) }} awaiting payment confirmation</p>
        @endif
      </div>

      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Volumes Sold</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950">{{ number_format($totalSoldUnits) }}</h3>
          <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">Copies</span>
        </div>
      </div>

      <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Active Titles</p>
        <div class="mt-3 flex items-end justify-between gap-3">
          <h3 class="text-3xl font-black tracking-tight text-slate-950">{{ number_format($totalBooks) }}</h3>
          <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">Listed</span>
        </div>
      </div>
    </div>

    <!-- Split Grid: Recent Orders & Recent Books -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
      
      <!-- Recent Sales Column -->
      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-7 space-y-6">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
          <div>
            <h3 class="text-base font-black text-slate-950">Recent Merchant Sales</h3>
            <p class="text-xs text-slate-400 mt-0.5">Purchases containing books from your atelier.</p>
          </div>
          <a href="{{ route('seller.orders.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">View All Orders →</a>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse($recentOrderItems as $orderItem)
            <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4 text-xs">
              <div class="min-w-0">
                <p class="font-serif font-bold text-slate-900 truncate">{{ $orderItem->title }}</p>
                <p class="text-slate-400 text-[11px] mt-0.5">
                  Order {{ $orderItem->order->order_number ?? 'N/A' }} · Qty {{ $orderItem->quantity }}
                </p>
              </div>

              <div class="text-right flex-shrink-0">
                <span class="font-serif font-bold text-slate-900 text-sm">${{ number_format($orderItem->line_total, 2) }}</span>
                <p class="text-[10px] text-slate-400">{{ $orderItem->created_at->diffForHumans() }}</p>
              </div>
            </div>
          @empty
            <p class="py-8 text-center text-xs text-slate-400">No book sales recorded yet.</p>
          @endforelse
        </div>
      </section>

      <!-- Recent Added Books Column -->
      <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-5 space-y-6">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
          <div>
            <h3 class="text-base font-black text-slate-950">Your Book Catalog</h3>
            <p class="text-xs text-slate-400 mt-0.5">Latest listed volumes.</p>
          </div>
          <a href="{{ route('seller.books.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition">Catalog →</a>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse($recentBooks as $book)
            <div class="flex items-center gap-3.5 py-3 first:pt-0 last:pb-0 transition hover:bg-slate-50 rounded-xl px-2">
              <img src="{{ $book->image_url ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=160' }}"
                   alt="{{ $book->title }}"
                   class="h-12 w-9 rounded-lg object-cover shadow-sm flex-shrink-0">
              <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-slate-900">{{ $book->title }}</p>
                <p class="text-[10px] text-slate-400">${{ number_format($book->price, 2) }} · {{ $book->stock }} in stock</p>
              </div>
              <a href="{{ route('seller.books.edit', $book->slug) }}" class="text-slate-400 hover:text-slate-900 text-xs font-bold">
                Edit
              </a>
            </div>
          @empty
            <div class="py-8 text-center text-xs text-slate-400">
              <p>No books added yet.</p>
              <a href="{{ route('seller.books.create') }}" class="mt-2 inline-block text-amber-600 font-bold underline">Add your first book</a>
            </div>
          @endforelse
        </div>
      </section>

    </div>
  </div>
</x-layout.seller-layout>
