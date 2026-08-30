<x-layout.admin-layout>
  <section class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-accent">Fulfillment control</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Order Management</h2>
        <p class="mt-2 text-sm text-slate-500">Confirm payments, update fulfillment progress, and handle cancellations.</p>
      </div>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold">
        {{ session('status') }}
      </div>
    @endif
    @if(session('error'))
      <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm font-semibold">
        {{ session('error') }}
      </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="relative md:col-span-2">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by order number, name, email, or reference..."
                 class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition">
        </div>
        <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
          <option value="all">All Statuses</option>
          @foreach(['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'] as $status)
            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <select name="payment_status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
          <option value="all">All Payments</option>
          @foreach(['pending', 'paid', 'failed', 'refunded'] as $ps)
            <option value="{{ $ps }}" {{ request('payment_status') === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
          @endforeach
        </select>
        <div class="md:col-span-4 flex items-center gap-2">
          <button type="submit" class="px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition">Filter</button>
          @if(request('q') || request('status') || request('payment_status'))
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">Clear</a>
          @endif
        </div>
      </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[860px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Order</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Customer</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Total</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Status</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Payment</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($orders as $order)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <p class="font-mono font-bold text-slate-900">{{ $order->order_number }}</p>
                  <p class="text-xs text-slate-500 mt-0.5">{{ $order->created_at->format('M d, Y — H:i') }}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm font-bold text-slate-900">{{ $order->shipping_name }}</p>
                  <p class="text-xs text-slate-500">{{ $order->shipping_email }}</p>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-black text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->total, 2) }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $order->status_color }}">{{ $order->status }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $order->payment_status_color }}">{{ str_replace('_', ' ', $order->payment_status) }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <a href="{{ route('admin.orders.show', $order->id) }}"
                     class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 transition hover:border-slate-900 hover:bg-slate-900 hover:text-white">
                    Manage →
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-16 text-center text-sm font-medium text-slate-500">No orders found matching your criteria.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $orders->links() }}</div>
      @endif
    </div>
  </section>
</x-layout.admin-layout>