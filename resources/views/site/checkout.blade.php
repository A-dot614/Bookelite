<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-12 space-y-2">
      <div class="flex items-center space-x-3">
        <span class="w-8 h-[1px] bg-gold"></span>
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">Final Dispatch</span>
      </div>
      <h1 class="text-4xl md:text-6xl font-serif text-slate-900 font-bold tracking-tight">
        Acquisition <span class="italic font-normal text-slate-400">Checkout.</span>
      </h1>
    </div>

    @if(session('error'))
      <div class="mb-8 rounded-2xl bg-red-50 p-6 border border-red-200">
        <p class="text-xs font-bold text-red-800">{{ session('error') }}</p>
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-8 rounded-2xl bg-red-50 p-6 border border-red-200">
        <div class="flex items-center gap-3 text-red-800 font-bold text-sm mb-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>Please complete the required details:</span>
        </div>
        <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
      @csrf

      <div class="grid lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left: Shipping & Dispatch Coordinates -->
        <div class="lg:col-span-7 space-y-8">
          
          <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
              <h2 class="text-base font-serif font-bold text-slate-900">1. Dispatch Address</h2>
              @guest
                <span class="text-xs text-slate-400">Already a patron? <a href="{{ route('login') }}" class="text-slate-900 underline font-bold">Sign in</a></span>
              @endguest
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Full Recipient Name</label>
                <input type="text" name="shipping_name" required 
                       value="{{ old('shipping_name', $user->name ?? '') }}"
                       placeholder="Lord Byron"
                       class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
              </div>

              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Email Address</label>
                <input type="email" name="shipping_email" required 
                       value="{{ old('shipping_email', $user->email ?? '') }}"
                       placeholder="patron@archive.com"
                       class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Contact Telephone</label>
                <input type="text" name="shipping_phone" 
                       value="{{ old('shipping_phone', $user->phone ?? '') }}"
                       placeholder="+1 (555) 019-2834"
                       class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
              </div>

              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Country / Region</label>
                <select name="shipping_country" 
                        class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition appearance-none">
                  @foreach(config('ecommerce.countries', []) as $country)
                    <option value="{{ $country }}" {{ old('shipping_country', 'United States') == $country ? 'selected' : '' }}>{{ $country }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Street Address</label>
              <input type="text" name="shipping_address" required 
                     value="{{ old('shipping_address', $user->address ?? '') }}"
                     placeholder="742 Evergreen Terrace, Suite 100"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">City</label>
                <input type="text" name="shipping_city" required 
                       value="{{ old('shipping_city') }}"
                       placeholder="New York"
                       class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
              </div>

              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Postal / ZIP Code</label>
                <input type="text" name="shipping_zip" required 
                       value="{{ old('shipping_zip') }}"
                       placeholder="10001"
                       class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
              </div>
            </div>
          </div>

          <!-- Payment Options -->
          <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6" x-data="{ method: '{{ $methods[0] ?? 'bank_transfer' }}' }">
            <h2 class="text-base font-serif font-bold text-slate-900 border-b border-slate-100 pb-4">2. Settlement Method</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              @foreach($methods as $methodKey)
                @php
                  $labels = [
                      'card' => 'Credit Card',
                      'paypal' => 'PayPal',
                      'bank_transfer' => 'Bank Wire',
                      'cod' => 'Cash on Delivery',
                  ];
                @endphp
                <label class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center text-center transition-all"
                       :class="method === '{{ $methodKey }}' ? 'border-slate-900 bg-slate-50 text-slate-900 font-bold' : 'border-slate-100 hover:border-slate-200 text-slate-500'">
                  <input type="radio" name="payment_method" value="{{ $methodKey }}" class="hidden"
                         @change="method = '{{ $methodKey }}'" {{ $loop->first ? 'checked' : '' }}>
                  <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $methodKey === 'card' ? 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z' : ($methodKey === 'bank_transfer' ? 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z' : ($methodKey === 'cod' ? 'M3 10h18M8 15v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z' : 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z')) }}"/>
                  <span class="text-xs">{{ $labels[$methodKey] ?? ucfirst($methodKey) }}</span>
                </label>
              @endforeach
            </div>

            @if(in_array('card', $methods))
              <div x-show="method === 'card'" class="space-y-4 pt-2 rounded-2xl bg-slate-50 p-5 text-xs text-slate-600">
                You will be redirected to a secure payment form after placing your order. Your card is never stored on this site.
              </div>
            @endif

            @if($paymentInstructions)
              <div x-show="method === 'bank_transfer' || method === 'cod'" class="space-y-2 pt-2 rounded-2xl bg-slate-50 p-5 text-xs text-slate-600">
                <p class="font-bold text-slate-900 uppercase tracking-widest text-[10px]">What happens next</p>
                <p>{{ $paymentInstructions }}</p>
              </div>
            @endif

            <div class="space-y-2 pt-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Curator Instructions / Notes (Optional)</label>
              <textarea name="notes" rows="2" placeholder="Special handling or gift inscription details..."
                        class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition resize-none"></textarea>
            </div>
          </div>

        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6 sticky top-28">
          <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h2 class="text-base font-serif font-bold text-slate-900">Order Artifacts</h2>
            <a href="{{ route('cart.index') }}" class="text-xs text-gold font-bold hover:underline">Edit Bag</a>
          </div>

          <!-- Items list -->
          <div class="space-y-4 max-h-72 overflow-y-auto pr-2 divide-y divide-slate-50">
            @foreach($items as $item)
              <div class="pt-3 first:pt-0 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                  <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}"
                       class="w-12 h-16 object-cover rounded-lg shadow-sm flex-shrink-0">
                  <div class="min-w-0">
                    <p class="text-xs font-serif font-bold text-slate-900 truncate">{{ $item['title'] }}</p>
                    <p class="text-[10px] text-slate-400">Qty: {{ $item['quantity'] }} × {{ config('ecommerce.currency_symbol') }}{{ number_format($item['price'], 2) }}</p>
                  </div>
                </div>
                <span class="text-xs font-bold text-slate-900 flex-shrink-0 font-serif">
                  {{ config('ecommerce.currency_symbol') }}{{ number_format($item['line_total'], 2) }}
                </span>
              </div>
            @endforeach
          </div>

          <!-- Promo code -->
          <div class="border-t border-slate-100 pt-4">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Promo Code</label>
            <div class="mt-2">
              <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="SAVE10"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition {{ $errors->has('coupon_code') ? 'ring-2 ring-red-300' : '' }}">
            </div>
            @error('coupon_code')
              <p class="mt-2 text-[10px] font-bold text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-[10px] text-slate-400">Redeemed automatically at settlement.</p>
          </div>

          <!-- Price calculations -->
          <div class="space-y-3 text-xs border-t border-slate-100 pt-4">
            <div class="flex justify-between text-slate-500">
              <span>Artifacts Subtotal</span>
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

          <!-- Grand Total -->
          <div class="flex justify-between items-baseline border-t border-slate-200 pt-4">
            <div>
              <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Total Due</span>
              <span class="text-xs text-slate-400 font-light">{{ config('ecommerce.currency') }} (incl. taxes)</span>
            </div>
            <span class="text-3xl font-serif font-bold text-slate-900">{{ config('ecommerce.currency_symbol') }}{{ number_format($summary['total'], 2) }}</span>
          </div>

          <button type="submit" 
                  class="w-full bg-[#141414] text-white py-5 rounded-full text-xs font-black uppercase tracking-[0.3em] hover:bg-slate-800 transition-all shadow-xl hover:-translate-y-0.5">
            Place Order
          </button>

          <p class="text-[10px] text-center text-slate-400 leading-relaxed">
            By placing your order, you agree to Elite Archive's <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Discretion Policy</a>. Orders are confirmed only once payment is received.
          </p>
        </div>

      </div>
    </form>

  </div>
</section>
</x-layout.main-layout>