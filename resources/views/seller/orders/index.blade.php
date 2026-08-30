<x-layout.seller-layout>
  <section class="space-y-6">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Merchant Dispatches</p>
      <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Customer Orders</h2>
      <p class="mt-2 text-sm text-slate-500">Track purchase transactions containing items from your curated inventory.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Item</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Order Reference</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Recipient</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Date</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Qty</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Total</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse($orderItems as $item)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    @if($item->image_url)
                      <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-10 h-14 object-cover rounded-lg shadow-xs flex-shrink-0">
                    @endif
                    <div class="min-w-0">
                      <p class="font-serif font-bold text-slate-900 text-sm truncate max-w-xs">{{ $item->title }}</p>
                      <p class="text-[11px] text-slate-400">by {{ $item->author }}</p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4">
                  <span class="font-mono text-xs font-bold text-slate-900">
                    {{ $item->order->order_number ?? 'N/A' }}
                  </span>
                </td>

                <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                  {{ $item->order->shipping_name ?? 'Anonymous' }}
                </td>

                <td class="px-6 py-4 text-xs text-slate-500">
                  {{ $item->created_at->format('M d, Y') }}
                </td>

                <td class="px-6 py-4 text-xs font-bold text-slate-900">
                  {{ $item->quantity }}
                </td>

                <td class="px-6 py-4 text-right">
                  <span class="text-sm font-serif font-bold text-slate-900">${{ number_format($item->line_total, 2) }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-16 text-center text-slate-500 text-sm">
                  No orders containing your books have been placed yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($orderItems->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
          {{ $orderItems->links() }}
        </div>
      @endif
    </div>
  </section>
</x-layout.seller-layout>
