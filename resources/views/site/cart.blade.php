<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-6xl mx-auto">
    
    <!-- Page Header -->
    <div class="mb-12 space-y-2">
      <div class="flex items-center space-x-3">
        <span class="w-8 h-[1px] bg-gold"></span>
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">Curated Selections</span>
      </div>
      <h1 class="text-4xl md:text-6xl font-serif text-slate-900 font-bold tracking-tight">
        Collection <span class="italic font-normal text-slate-400">Bag.</span>
      </h1>
    </div>

    @if (session('status'))
      <div class="mb-8 rounded-full bg-slate-900 text-white px-6 py-3.5 text-xs font-bold uppercase tracking-widest flex items-center gap-3 shadow-lg">
        <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
        {{ session('status') }}
      </div>
    @endif

    @if(session('error'))
      <div class="mb-8 rounded-full bg-red-600 text-white px-6 py-3.5 text-xs font-bold uppercase tracking-widest flex items-center gap-3 shadow-lg">
        {{ session('error') }}
      </div>
    @endif

    @if(!empty($items))
      @if($notice)
        <div class="mb-8 rounded-full bg-amber-50 border border-amber-200 text-amber-800 px-6 py-3 text-xs font-semibold tracking-wide flex items-center gap-3">
          {{ $notice }}
        </div>
      @endif

      <div class="grid lg:grid-cols-12 gap-12 items-start">
        
        <!-- Cart Line Items -->
        <div class="lg:col-span-8 space-y-6">
          <div class="bg-white rounded-3xl border border-slate-100 p-6 md:p-8 shadow-sm divide-y divide-slate-100">
            @foreach($items as $id => $item)
              <div class="py-6 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                
                <div class="flex items-center gap-5 min-w-0">
                  <a href="{{ route('detail', $item['slug']) }}" class="flex-shrink-0">
                    <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}"
                         class="w-20 h-28 object-cover rounded-xl shadow-sm hover:scale-105 transition-transform duration-300">
                  </a>
                  <div class="min-w-0">
                    <a href="{{ route('detail', $item['slug']) }}" class="text-base font-serif font-bold text-slate-900 hover:text-gold transition block truncate max-w-xs">
                      {{ $item['title'] }}
                    </a>
                    <p class="text-xs text-slate-400 mt-1">by {{ $item['author'] }}</p>
                    @if($item['low_stock'])
                      <p class="text-[10px] font-bold text-amber-600 mt-1">Only {{ $item['stock'] }} left in stock</p>
                    @endif
                    <p class="text-sm font-bold text-slate-900 mt-2">{{ config('ecommerce.currency_symbol') }}{{ number_format($item['price'], 2) }} <span class="text-[10px] text-slate-400 font-normal">/ unit</span></p>
                  </div>
                </div>

                <!-- Quantity & Total -->
                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                  <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center border border-slate-200 rounded-full bg-[#f0f0f0] px-3 py-1.5">
                      <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}"
                             class="w-12 bg-transparent text-xs font-black text-slate-900 text-center focus:outline-none">
                    </div>
                    <button type="submit" title="Update quantity"
                            class="p-2 rounded-full text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                  </form>

                  <div class="text-right min-w-[80px]">
                    <p class="text-base font-serif font-bold text-slate-900">
                      {{ config('ecommerce.currency_symbol') }}{{ number_format($item['line_total'], 2) }}
                    </p>
                  </div>

                  <!-- Remove action -->
                  <form action="{{ route('cart.remove', $id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Remove item"
                            class="p-2 rounded-full text-slate-300 hover:text-red-500 hover:bg-red-50 transition">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </form>
                </div>

              </div>
            @endforeach
          </div>

          <div class="flex items-center justify-between pt-2">
            <a href="{{ route('home') }}" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition flex items-center gap-2">
              ← Continue Browsing
            </a>

            <form action="{{ route('cart.clear') }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-red-600 transition">
                Clear Bag
              </button>
            </form>
          </div>
        </div>

        <!-- Order Summary & Checkout -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
          <h2 class="text-lg font-serif font-bold text-slate-900">Archive Order Summary</h2>

          <div class="space-y-4 text-sm border-y border-slate-100 py-6">
            <div class="flex justify-between text-slate-500">
              <span>Selected Works ({{ count($items) }})</span>
              <span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($summary['subtotal'], 2) }}</span>
            </div>

            <div class="flex justify-between text-slate-500">
              <span>Dispatch</span>
              @if($summary['shipping_cost'] > 0)
                <span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($summary['shipping_cost'], 2) }}</span>
              @else
                <span class="font-bold text-emerald-600">{{ config('ecommerce.free_shipping_label') }}</span>
              @endif
            </div>

            <div class="flex justify-between text-slate-500">
              <span>Tax</span>
              <span class="font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($summary['tax_amount'], 2) }}</span>
            </div>
          </div>

          <div class="flex justify-between items-baseline pt-2">
            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Investment</span>
            <span class="text-3xl font-serif font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($summary['total'], 2) }}</span>
          </div>

          <a href="{{ route('checkout.index') }}" 
             class="w-full block text-center bg-[#141414] text-white py-5 rounded-full text-xs font-black uppercase tracking-[0.25em] hover:bg-slate-800 transition-all shadow-md">
            Proceed to Checkout
          </a>

          <div class="flex items-center justify-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest pt-2">
            <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span>Encrypted & Confidential Checkout</span>
          </div>
        </div>

      </div>
    @else
      <!-- Empty Bag State -->
      <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center max-w-xl mx-auto shadow-sm space-y-6">
        <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <div>
          <h3 class="text-2xl font-serif font-bold text-slate-900">Your bag is empty.</h3>
          <p class="text-sm text-slate-500 font-light mt-2 max-w-sm mx-auto">
            Discover our curated archive of rare and distinguished masterpieces to begin your collection.
          </p>
        </div>
        <a href="{{ route('home') }}" 
           class="inline-block bg-[#141414] text-white px-8 py-4 rounded-full text-xs font-black uppercase tracking-[0.25em] hover:bg-slate-800 transition shadow-md">
          Explore the Archive
        </a>
      </div>
    @endif

  </div>
</section>
</x-layout.main-layout>