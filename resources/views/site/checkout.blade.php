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
                  <option value="United States" {{ old('shipping_country') == 'United States' ? 'selected' : '' }}>United States</option>
                  <option value="United Kingdom" {{ old('shipping_country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                  <option value="France" {{ old('shipping_country') == 'France' ? 'selected' : '' }}>France</option>
                  <option value="Germany" {{ old('shipping_country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                  <option value="Canada" {{ old('shipping_country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                  <option value="Australia" {{ old('shipping_country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                  <option value="Japan" {{ old('shipping_country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                  <option value="International" {{ old('shipping_country') == 'International' ? 'selected' : '' }}>Other International</option>
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
          <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6" x-data="{ method: 'card' }">
            <h2 class="text-base font-serif font-bold text-slate-900 border-b border-slate-100 pb-4">2. Settlement Method</h2>

            <div class="grid grid-cols-3 gap-4">
              <label class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center text-center transition-all"
                     :class="method === 'card' ? 'border-slate-900 bg-slate-50 text-slate-900 font-bold' : 'border-slate-100 hover:border-slate-200 text-slate-500'">
                <input type="radio" name="payment_method" value="card" class="hidden" @change="method = 'card'" checked>
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <span class="text-xs">Credit Card</span>
              </label>

              <label class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center text-center transition-all"
                     :class="method === 'paypal' ? 'border-slate-900 bg-slate-50 text-slate-900 font-bold' : 'border-slate-100 hover:border-slate-200 text-slate-500'">
                <input type="radio" name="payment_method" value="paypal" class="hidden" @change="method = 'paypal'">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-xs">PayPal</span>
              </label>

              <label class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center text-center transition-all"
                     :class="method === 'bank_transfer' ? 'border-slate-900 bg-slate-50 text-slate-900 font-bold' : 'border-slate-100 hover:border-slate-200 text-slate-500'">
                <input type="radio" name="payment_method" value="bank_transfer" class="hidden" @change="method = 'bank_transfer'">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                <span class="text-xs">Bank Wire</span>
              </label>
            </div>

            <!-- Card inputs mockup -->
            <div x-show="method === 'card'" class="space-y-4 pt-2">
              <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Card Number</label>
                <input type="text" placeholder="•••• •••• •••• 4242" value="4242 •••• •••• 4242"
                       class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-mono font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                  <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Expiration</label>
                  <input type="text" placeholder="MM / YY" value="12 / 28"
                         class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-center">
                </div>
                <div class="space-y-2">
                  <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">CVC / CVV</label>
                  <input type="text" placeholder="CVC" value="888"
                         class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-center">
                </div>
              </div>
            </div>

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
            @foreach($cart as $item)
              <div class="pt-3 first:pt-0 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                  <img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}"
                       class="w-12 h-16 object-cover rounded-lg shadow-sm flex-shrink-0">
                  <div class="min-w-0">
                    <p class="text-xs font-serif font-bold text-slate-900 truncate">{{ $item['title'] }}</p>
                    <p class="text-[10px] text-slate-400">Qty: {{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</p>
                  </div>
                </div>
                <span class="text-xs font-bold text-slate-900 flex-shrink-0 font-serif">
                  ${{ number_format($item['price'] * $item['quantity'], 2) }}
                </span>
              </div>
            @endforeach
          </div>

          <!-- Price calculations -->
          <div class="space-y-3 text-xs border-t border-slate-100 pt-4">
            <div class="flex justify-between text-slate-500">
              <span>Artifacts Subtotal</span>
              <span class="font-bold text-slate-900">${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between text-slate-500">
              <span>White-Glove Courier</span>
              <span class="font-bold text-emerald-600">Complimentary</span>
            </div>
            <div class="flex justify-between text-slate-500">
              <span>Import & Archive Duties</span>
              <span class="font-bold text-slate-900">$0.00</span>
            </div>
          </div>

          <!-- Grand Total -->
          <div class="flex justify-between items-baseline border-t border-slate-200 pt-4">
            <div>
              <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Total Due</span>
              <span class="text-xs text-slate-400 font-light">USD (incl. taxes)</span>
            </div>
            <span class="text-3xl font-serif font-bold text-slate-900">${{ number_format($total, 2) }}</span>
          </div>

          <button type="submit" 
                  class="w-full bg-[#141414] text-white py-5 rounded-full text-xs font-black uppercase tracking-[0.3em] hover:bg-slate-800 transition-all shadow-xl hover:-translate-y-0.5">
            Authorize Acquisition
          </button>

          <p class="text-[10px] text-center text-slate-400 leading-relaxed">
            By placing your order, you agree to Elite Archive's <a href="#" class="underline">Terms of Service</a> and <a href="#" class="underline">Discretion Policy</a>.
          </p>
        </div>

      </div>
    </form>

  </div>
</section>
</x-layout.main-layout>
