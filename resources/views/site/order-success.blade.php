<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-4xl mx-auto space-y-12">
    
    <!-- Success Banner -->
    <div class="text-center space-y-4">
      <div class="w-16 h-16 bg-slate-900 text-gold rounded-full flex items-center justify-center mx-auto shadow-xl">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
      </div>

      <span class="text-[10px] font-black uppercase tracking-[0.5em] text-gold block">Order Registered</span>
      <h1 class="text-4xl md:text-5xl font-serif text-slate-900 font-bold tracking-tight">
        Acquisition Registered.
      </h1>
      <p class="text-sm text-slate-500 font-light max-w-md mx-auto">
        Your order <span class="font-mono font-bold text-slate-900">{{ $order->order_number }}</span> has been placed into our archival fulfillment queue.
      </p>

      @if($order->payment_status === 'pending')
        <div class="max-w-md mx-auto rounded-2xl bg-amber-50 border border-amber-200 p-4 text-left space-y-1">
          <p class="text-[10px] font-black uppercase tracking-widest text-amber-700">Payment Pending</p>
          <p class="text-xs text-amber-800 leading-relaxed">
            Your order is <strong>not yet confirmed</strong>. Once your {{ str_replace('_', ' ', $order->payment_method) }} payment is received
            it will be marked as paid. Reference: <span class="font-mono font-bold">{{ $order->payment_reference }}</span>
          </p>
        </div>
      @elseif($order->payment_status === 'paid')
        <div class="max-w-md mx-auto rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-left">
          <p class="text-[10px] font-black uppercase tracking-widest text-emerald-700">Payment Received</p>
          <p class="text-xs text-emerald-800 leading-relaxed">Thank you — your payment has been confirmed and your order is being prepared.</p>
        </div>
      @endif
    </div>

    <!-- Order Manifest Card -->
    <div class="bg-white rounded-3xl border border-slate-100 p-8 md:p-10 shadow-sm space-y-8">
      
      <!-- Key Meta Row -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 border-b border-slate-100 pb-8 text-xs">
        <div>
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Order Reference</p>
          <p class="font-mono font-bold text-slate-900 mt-1">{{ $order->order_number }}</p>
        </div>
        <div>
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Date Placed</p>
          <p class="font-bold text-slate-900 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <div>
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Settlement</p>
          <p class="font-bold text-slate-900 uppercase mt-1">{{ str_replace('_', ' ', $order->payment_method) }}</p>
        </div>
        <div>
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Status</p>
          <span class="inline-flex rounded-full px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $order->payment_status_color }}">
            {{ str_replace('_', ' ', $order->payment_status) }}
          </span>
        </div>
      </div>

      <!-- Items Ordered -->
      <div>
        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Acquired Masterpieces</h3>
        
        <div class="divide-y divide-slate-100">
          @foreach($order->items as $item)
            <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
              <div class="flex items-center gap-4 min-w-0">
                @if($item->image_url)
                  <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-14 h-18 object-cover rounded-lg shadow-sm flex-shrink-0">
                @endif
                <div class="min-w-0">
                  <p class="text-sm font-serif font-bold text-slate-900 truncate">{{ $item->title }}</p>
                  <p class="text-xs text-slate-400">by {{ $item->author }}</p>
                  <p class="text-[11px] text-slate-500 mt-1">Quantity: {{ $item->quantity }} × {{ config('ecommerce.currency_symbol') }}{{ number_format($item->price, 2) }}</p>
                </div>
              </div>

              <span class="text-sm font-serif font-bold text-slate-900 flex-shrink-0">
                {{ config('ecommerce.currency_symbol') }}{{ number_format($item->line_total, 2) }}
              </span>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Shipping Destination -->
      <div class="grid md:grid-cols-2 gap-8 border-t border-slate-100 pt-8 text-xs">
        <div class="space-y-1">
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Dispatch Destination</p>
          <p class="font-bold text-slate-900 text-sm">{{ $order->shipping_name }}</p>
          <p class="text-slate-600">{{ $order->shipping_address }}</p>
          <p class="text-slate-600">{{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
          <p class="text-slate-600">{{ $order->shipping_country }}</p>
          @if($order->shipping_phone)
            <p class="text-slate-500 mt-2">Tel: {{ $order->shipping_phone }}</p>
          @endif
        </div>

        <div class="space-y-3 bg-slate-50 p-6 rounded-2xl border border-slate-100">
          <div class="flex justify-between text-slate-500">
            <span>Subtotal</span>
            <span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->subtotal, 2) }}</span>
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Dispatch</span>
            @if($order->shipping_cost > 0)
              <span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->shipping_cost, 2) }}</span>
            @else
              <span class="font-bold text-emerald-600">{{ config('ecommerce.free_shipping_label') }}</span>
            @endif
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Tax</span>
            <span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->tax_amount, 2) }}</span>
          </div>
          <div class="flex justify-between items-baseline border-t border-slate-200 pt-3">
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Investment</span>
            <span class="text-2xl font-serif font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($order->total, 2) }}</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Next Actions -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
      <a href="{{ route('home') }}" 
         class="w-full sm:w-auto bg-[#141414] text-white px-10 py-4 rounded-full text-xs font-black uppercase tracking-[0.25em] hover:bg-slate-800 transition text-center shadow-md">
        Continue Exploring Archive
      </a>

      @auth
        <a href="{{ route('orders.index') }}" 
           class="w-full sm:w-auto border border-slate-200 bg-white text-slate-700 px-10 py-4 rounded-full text-xs font-bold uppercase tracking-[0.25em] hover:border-slate-900 hover:text-slate-900 transition text-center">
          View Order History
        </a>
      @endauth
    </div>

  </div>
</section>
</x-layout.main-layout>