<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
      <a href="{{ route('orders.index') }}" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition flex items-center gap-2">
        ← Back to Order Ledger
      </a>

      <span class="inline-flex rounded-full px-3.5 py-1 text-xs font-bold uppercase tracking-wider {{ $order->status_color }}">
        Status: {{ $order->status }}
      </span>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-slate-100 p-8 md:p-10 shadow-sm space-y-8">
      
      <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-4 border-b border-slate-100 pb-6">
        <div>
          <span class="text-[10px] font-black uppercase tracking-[0.3em] text-gold">Order Manifest</span>
          <h1 class="text-2xl md:text-3xl font-serif font-bold text-slate-900 mt-1">{{ $order->order_number }}</h1>
        </div>
        <p class="text-xs text-slate-400">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
      </div>

      <!-- Items Section -->
      <div class="space-y-4">
        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Artifacts Included</h3>
        
        <div class="divide-y divide-slate-100">
          @foreach($order->items as $item)
            <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
              <div class="flex items-center gap-4 min-w-0">
                @if($item->image_url)
                  <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-14 h-20 object-cover rounded-xl shadow-sm flex-shrink-0">
                @endif
                <div class="min-w-0">
                  <h4 class="text-sm font-serif font-bold text-slate-900 truncate">
                    @if($item->book)
                      <a href="{{ route('detail', $item->book->slug) }}" class="hover:text-gold transition">{{ $item->title }}</a>
                    @else
                      {{ $item->title }}
                    @endif
                  </h4>
                  <p class="text-xs text-slate-400">by {{ $item->author }}</p>
                  <p class="text-xs text-slate-500 mt-1">Quantity: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                </div>
              </div>

              <span class="text-base font-serif font-bold text-slate-900 flex-shrink-0">
                ${{ number_format($item->line_total, 2) }}
              </span>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Shipping & Totals Breakdown -->
      <div class="grid md:grid-cols-2 gap-8 border-t border-slate-100 pt-8 text-xs">
        <div class="space-y-2">
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Recipient & Delivery Destination</p>
          <p class="text-sm font-bold text-slate-900 pt-1">{{ $order->shipping_name }}</p>
          <p class="text-slate-600">{{ $order->shipping_address }}</p>
          <p class="text-slate-600">{{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
          <p class="text-slate-600">{{ $order->shipping_country }}</p>
          @if($order->shipping_phone)
            <p class="text-slate-500 pt-1">Telephone: {{ $order->shipping_phone }}</p>
          @endif
          @if($order->notes)
            <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
              <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Instructions</p>
              <p class="text-slate-700 italic mt-1">{{ $order->notes }}</p>
            </div>
          @endif
        </div>

        <div class="space-y-3 bg-slate-50 p-6 rounded-2xl border border-slate-100 flex flex-col justify-between">
          <div class="space-y-2.5">
            <div class="flex justify-between text-slate-500">
              <span>Subtotal</span>
              <span class="font-bold text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-slate-500">
              <span>Shipping Courier</span>
              <span class="font-bold text-emerald-600">Complimentary</span>
            </div>
            @if((float) $order->discount_amount > 0)
              <div class="flex justify-between text-emerald-700">
                <span>Promo Discount {{ $order->coupon_code ? '('.$order->coupon_code.')' : '' }}</span>
                <span class="font-bold">−${{ number_format($order->discount_amount, 2) }}</span>
              </div>
            @endif
            <div class="flex justify-between text-slate-500">
              <span>Settlement</span>
              <span class="font-bold text-slate-900 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
            </div>
          </div>

          <div class="flex justify-between items-baseline border-t border-slate-200 pt-3 mt-4">
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Settled</span>
            <span class="text-2xl font-serif font-bold text-slate-900">${{ number_format($order->total, 2) }}</span>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>
</x-layout.main-layout>
