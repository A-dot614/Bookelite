<x-layout.admin-layout>
  <section class="space-y-6">
    <div class="flex items-center justify-between">
      <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition">← Back to Orders</a>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold">{{ session('status') }}</div>
    @endif
    @if(session('error'))
      <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm font-semibold">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

      <!-- Order details -->
      <div class="lg:col-span-8 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
              <p class="text-xs font-bold uppercase tracking-widest text-accent">Order {{ $order->order_number }}</p>
              <p class="mt-1 text-xs text-slate-500">Placed {{ $order->created_at->format('M d, Y — H:i') }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $order->status_color }}">{{ $order->status }}</span>
              <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $order->payment_status_color }}">{{ str_replace('_', ' ', $order->payment_status) }}</span>
            </div>
          </div>

          <div class="divide-y divide-slate-100">
            @foreach($order->items as $item)
              <div class="py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                  @if($item->image_url)
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-12 h-16 object-cover rounded-lg shadow-sm flex-shrink-0">
                  @endif
                  <div class="min-w-0">
                    <p class="text-sm font-serif font-bold text-slate-900 truncate">{{ $item->title }}</p>
                    <p class="text-xs text-slate-400">by {{ $item->author }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">Qty {{ $item->quantity }} × {{ config('ecommerce.currency_symbol') }}{{ number_format($item->price, 2) }}</p>
                    @if($item->sku || $item->isbn)
                      <p class="text-[10px] text-slate-400 font-mono">{{ $item->sku ?? '' }}{{ $item->isbn ? ' · ISBN '.$item->isbn : '' }}</p>
                    @endif
                  </div>
                </div>
                <span class="font-serif font-bold text-slate-900 flex-shrink-0">{{ config('ecommerce.currency_symbol') }}{{ number_format($item->line_total, 2) }}</span>
              </div>
            @endforeach
          </div>

          <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
            <div class="flex justify-between text-slate-500"><span>Subtotal</span><span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between text-slate-500"><span>Shipping</span><span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->shipping_cost, 2) }}</span></div>
            <div class="flex justify-between text-slate-500"><span>Tax</span><span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->tax_amount, 2) }}</span></div>
            @if((float) $order->discount_amount > 0)
              <div class="flex justify-between text-emerald-700"><span>Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</span><span class="font-bold">−{{ config('ecommerce.currency_symbol') }}{{ number_format($order->discount_amount, 2) }}</span></div>
            @endif
            <div class="flex justify-between text-slate-900 font-black pt-2 border-t border-slate-100"><span>Total</span><span>{{ config('ecommerce.currency_symbol') }}{{ number_format($order->total, 2) }} <span class="text-xs font-bold text-slate-400">{{ $order->currency }}</span></span></div>
          </div>
        </div>

        <!-- Shipping details -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm text-sm">
          <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Dispatch Destination</h3>
          <p class="font-bold text-slate-900">{{ $order->shipping_name }}</p>
          <p class="text-slate-600">{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_zip }}</p>
          <p class="text-slate-600">{{ $order->shipping_country }}</p>
          @if($order->shipping_phone)<p class="text-slate-500 mt-1">Tel: {{ $order->shipping_phone }}</p>@endif
          @if($order->notes)<p class="text-slate-600 mt-3 italic">"{{ $order->notes }}"</p>@endif
        </div>

        <!-- Inventory / payment trail -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm text-sm space-y-2">
          <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Audit Trail</h3>
          <div class="flex justify-between"><span class="text-slate-500">Payment method</span><span class="font-bold text-slate-900 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span></div>
          <div class="flex justify-between"><span class="text-slate-500">Payment reference</span><span class="font-mono font-bold text-slate-900">{{ $order->payment_reference ?? '—' }}</span></div>
          <div class="flex justify-between"><span class="text-slate-500">Tracking number</span><span class="font-mono font-bold text-slate-900">{{ $order->tracking_number ?? '—' }}</span></div>
          <div class="flex justify-between"><span class="text-slate-500">Inventory restored on cancel/refund</span><span class="font-bold text-slate-900">{{ $order->stock_restored ? 'Yes' : 'No' }}</span></div>
        </div>
      </div>

      <!-- Management column -->
      <div class="lg:col-span-4 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-5">
          <h3 class="text-base font-black text-slate-950">Order Actions</h3>

          @if(!$order->isPaid() && !in_array($order->status, ['cancelled', 'refunded'], true))
            <form action="{{ route('admin.orders.mark-paid', $order->id) }}" method="POST">
              @csrf
              <button type="submit" class="w-full rounded-full bg-emerald-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-emerald-700 shadow-sm">
                Confirm Payment Received
              </button>
            </form>
          @endif

          @if(count($allowedTransitions) > 0)
            <form action="{{ route('admin.orders.transition', $order->id) }}" method="POST" class="space-y-3">
              @csrf
              <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Update Fulfillment Status</label>
              <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
                @foreach($allowedTransitions as $transition)
                  <option value="{{ $transition }}">{{ ucfirst($transition) }}</option>
                @endforeach
              </select>

              <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Tracking Number</label>
              <input type="text" name="tracking_number" placeholder="Optional"
                     class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">

              <button type="submit" class="w-full rounded-full bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-700 shadow-sm">
                Apply
              </button>
            </form>
          @else
            <p class="text-xs text-slate-400">This order is in a final state and cannot be changed.</p>
          @endif

          @if($order->payment_status === 'pending' && $order->status === 'pending')
            <form action="{{ route('admin.orders.transition', $order->id) }}" method="POST"
                  onsubmit="return confirm('Cancel this order? Its stock will be returned to inventory.');">
              @csrf
              <input type="hidden" name="status" value="cancelled">
              <button type="submit" class="w-full rounded-full border border-red-200 px-5 py-3 text-xs font-black uppercase tracking-widest text-red-600 transition hover:bg-red-50">
                Cancel Order
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  </section>
</x-layout.admin-layout>