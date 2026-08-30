<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-5xl mx-auto space-y-10">
    
    <div class="space-y-2">
      <div class="flex items-center space-x-3">
        <span class="w-8 h-[1px] bg-gold"></span>
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">Patron History</span>
      </div>
      <h1 class="text-4xl md:text-5xl font-serif text-slate-900 font-bold tracking-tight">
        Acquisition <span class="italic font-normal text-slate-400">Ledger.</span>
      </h1>
      <p class="text-xs text-slate-500 font-light">
        A permanent record of your curated literary acquisitions and shipments.
      </p>
    </div>

    @if($orders->count() > 0)
      <div class="space-y-4">
        @foreach($orders as $order)
          <div class="bg-white rounded-3xl border border-slate-100 p-6 md:p-8 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
              
              <div class="space-y-2">
                <div class="flex items-center gap-3">
                  <span class="font-mono text-sm font-bold text-slate-900">{{ $order->order_number }}</span>
                  <span class="inline-flex rounded-full px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $order->status_color }}">
                    {{ $order->status }}
                  </span>
                </div>
                <p class="text-xs text-slate-400">
                  Placed on {{ $order->created_at->format('M d, Y — H:i') }} · {{ $order->items->count() }} item(s)
                </p>
              </div>

              <!-- Thumbnails preview -->
              <div class="flex items-center gap-2 overflow-x-auto py-1">
                @foreach($order->items->take(3) as $orderItem)
                  @if($orderItem->image_url)
                    <img src="{{ $orderItem->image_url }}" alt="{{ $orderItem->title }}" class="w-10 h-14 object-cover rounded-lg shadow-xs" title="{{ $orderItem->title }}">
                  @endif
                @endforeach
                @if($order->items->count() > 3)
                  <span class="text-[10px] font-bold text-slate-400 pl-1">+{{ $order->items->count() - 3 }} more</span>
                @endif
              </div>

              <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                <div class="text-left md:text-right">
                  <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 block">Total</span>
                  <span class="text-lg font-serif font-bold text-slate-900">${{ number_format($order->total, 2) }}</span>
                </div>

                <a href="{{ route('orders.show', $order->id) }}" 
                   class="bg-[#141414] text-white px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-sm">
                  View Manifest →
                </a>
              </div>

            </div>
          </div>
        @endforeach
      </div>

      @if($orders->hasPages())
        <div class="pt-6 flex justify-center">
          {{ $orders->links() }}
        </div>
      @endif
    @else
      <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center max-w-xl mx-auto shadow-sm space-y-6">
        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
          <h3 class="text-2xl font-serif font-bold text-slate-900">No acquisitions recorded yet.</h3>
          <p class="text-xs text-slate-500 font-light mt-2 max-w-sm mx-auto">
            When you complete an order, your invoice and shipment progress will be archived here.
          </p>
        </div>
        <a href="{{ route('home') }}" 
           class="inline-block bg-[#141414] text-white px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-md">
          Explore Archive
        </a>
      </div>
    @endif

  </div>
</section>
</x-layout.main-layout>
