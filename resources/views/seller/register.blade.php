<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-3xl mx-auto space-y-10">
    
    <div class="text-center space-y-3">
      <div class="w-12 h-12 bg-slate-900 text-gold rounded-full flex items-center justify-center mx-auto shadow-md">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
      </div>
      <span class="text-[10px] font-black uppercase tracking-[0.5em] text-gold block">Merchant Onboarding</span>
      <h1 class="text-4xl md:text-5xl font-serif text-slate-900 font-bold tracking-tight">
        Open Your Book Studio.
      </h1>
      <p class="text-sm text-slate-500 font-light max-w-md mx-auto">
        Join our elite network of independent curators, publishers, and antiquarian booksellers.
      </p>
    </div>

    @if ($errors->any())
      <div class="rounded-2xl bg-red-50 p-6 border border-red-200 text-xs text-red-700">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 p-8 md:p-10 shadow-sm">
      <form action="{{ route('seller.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Store / Merchant Name</label>
          <input type="text" name="store_name" required value="{{ old('store_name') }}"
                 placeholder="e.g. Oxford Archival Books"
                 class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Direct Telephone</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   placeholder="+1 (555) 302-8821"
                   class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
          </div>

          <div class="space-y-2">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Physical Atelier / City</label>
            <input type="text" name="address" value="{{ old('address') }}"
                   placeholder="London / New York"
                   class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
          </div>
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Store Biography & Specialization</label>
          <textarea name="bio" rows="4"
                    placeholder="Tell patrons about your curation history, rare book collections, and focus genres..."
                    class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition resize-none leading-relaxed">{{ old('bio') }}</textarea>
        </div>

        <div class="pt-4">
          <button type="submit" 
                  class="w-full bg-[#141414] text-white py-5 rounded-full text-xs font-black uppercase tracking-[0.25em] hover:bg-slate-800 transition-all shadow-md">
            Establish Merchant Studio
          </button>
        </div>
      </form>
    </div>

  </div>
</section>
</x-layout.main-layout>
