<x-layout.admin-layout>
  <section class="space-y-8">
    
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-accent">Performance Analytics</p>
      <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Financial & Catalog Reports</h2>
      <p class="mt-2 text-sm text-slate-500">Comprehensive overview of platform revenue, top acquisition items, and category performance.</p>
      <a href="{{ route('admin.reports.export') }}"
         class="mt-4 inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.25em] text-white hover:bg-accent transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Export CSV
      </a>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Settled Revenue</p>
        <div class="mt-3 flex items-baseline justify-between">
          <h3 class="text-3xl font-black text-slate-950 font-serif">${{ number_format($totalRevenue, 2) }}</h3>
          <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Gross Sales</span>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Dispatches Placed</p>
        <div class="mt-3 flex items-baseline justify-between">
          <h3 class="text-3xl font-black text-slate-950">{{ number_format($totalOrdersCount) }}</h3>
          <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700">Orders</span>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Average Order Value</p>
        <div class="mt-3 flex items-baseline justify-between">
          <h3 class="text-3xl font-black text-slate-950 font-serif">${{ number_format($avgOrderValue, 2) }}</h3>
          <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">AOV</span>
        </div>
      </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-base font-black text-slate-950">Revenue Trend — Last 12 Months</h3>
          <p class="text-xs text-slate-400 mt-0.5">Paid orders only; bar height reflects settled revenue.</p>
        </div>
        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">{{ number_format($totalUnitsSold) }} units sold</span>
      </div>

      <div class="flex items-end justify-between gap-2 sm:gap-3 h-48">
        @php $maxRevenue = max($monthlyRevenue->max('revenue'), 1); @endphp
        @foreach($monthlyRevenue as $label => $data)
          <div class="flex-1 flex flex-col items-center gap-2 group">
            <div class="relative w-full flex items-end justify-center rounded-t-lg bg-slate-100 group-hover:bg-slate-200 transition"
                 style="height: 100%;">
              <div class="absolute bottom-0 w-full rounded-t-lg bg-gradient-to-t from-slate-900 to-accent transition-all duration-500"
                   style="height: {{ max($data['revenue'] / $maxRevenue * 100, 2) }}%;">
                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[9px] font-black text-slate-600 opacity-0 group-hover:opacity-100 whitespace-nowrap transition">${{ number_format($data['revenue'], 0) }}</span>
              </div>
            </div>
            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</span>
            <span class="text-[8px] text-slate-300">{{ $data['orders'] }} ord</span>
          </div>
        @endforeach
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      
      <!-- Top Selling Books -->
      <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
        <div>
          <h3 class="text-base font-black text-slate-950">Top Selling Masterpieces</h3>
          <p class="text-xs text-slate-400 mt-0.5">Most frequently acquired titles in the collection.</p>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-100">
              <tr>
                <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Title</th>
                <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Units Sold</th>
                <th class="py-3 px-4 text-right font-black uppercase tracking-widest text-slate-500">Revenue</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @forelse($topBooks as $book)
                <tr class="hover:bg-slate-50 transition">
                  <td class="py-3.5 px-4 font-bold text-slate-900 truncate max-w-xs">{{ $book->title }}</td>
                  <td class="py-3.5 px-4 font-bold text-slate-700">{{ $book->total_sold }} units</td>
                  <td class="py-3.5 px-4 text-right font-serif font-bold text-slate-900">${{ number_format($book->total_revenue, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="py-8 text-center text-slate-400">No sales transactions recorded yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Category Breakdown -->
      <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
        <div>
          <h3 class="text-base font-black text-slate-950">Revenue by Genre / Category</h3>
          <p class="text-xs text-slate-400 mt-0.5">Sales distribution across curation categories.</p>
        </div>

        <div class="space-y-4">
          @php $maxCatRev = $categoryBreakdown->max('category_revenue') ?: 1; @endphp
          @forelse($categoryBreakdown as $cat)
            <div class="space-y-1.5">
              <div class="flex justify-between text-xs font-bold">
                <span class="text-slate-800">{{ $cat->category ?? 'Uncategorized' }}</span>
                <span class="text-slate-900 font-serif">${{ number_format($cat->category_revenue, 2) }} ({{ $cat->items_count }} sold)</span>
              </div>
              <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full bg-slate-900 rounded-full transition-all duration-500"
                     style="width: {{ min(100, round(($cat->category_revenue / $maxCatRev) * 100)) }}%;"></div>
              </div>
            </div>
          @empty
            <p class="text-xs text-slate-400 py-8 text-center">No category data yet.</p>
          @endforelse
        </div>
      </div>

    </div>

    <!-- Recent Orders Stream -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-base font-black text-slate-950">Recent Acquisition Dispatches</h3>
          <p class="text-xs text-slate-400 mt-0.5">Latest order transactions placed on the system.</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Order Ref</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Customer</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Date</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Status</th>
              <th class="py-3 px-4 text-right font-black uppercase tracking-widest text-slate-500">Total</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
              <tr class="hover:bg-slate-50 transition">
                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $order->order_number }}</td>
                <td class="py-3.5 px-4 font-bold text-slate-700">{{ $order->shipping_name }}</td>
                <td class="py-3.5 px-4 text-slate-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                <td class="py-3.5 px-4">
                  <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $order->status_color }}">
                    {{ $order->status }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-right font-serif font-bold text-slate-900">${{ number_format($order->total, 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-8 text-center text-slate-400">No orders placed yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </section>
</x-layout.admin-layout>
