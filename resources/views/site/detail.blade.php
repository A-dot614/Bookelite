<x-layout.main-layout>
<section class="relative py-24 px-6 bg-[#fafafa] overflow-hidden min-h-screen flex items-center">
  <div class="absolute top-0 left-0 w-full h-full text-[25vw] font-black text-slate-900/[0.01] select-none -z-10 leading-none flex items-center justify-center">
    EDITION
  </div>

  <div class="max-w-7xl mx-auto w-full">
    <div class="grid lg:grid-cols-12 gap-20 items-start">
      
      <div class="lg:col-span-6 lg:sticky lg:top-32">
        <div class="relative group">
          <div class="relative z-10 aspect-[3/4] max-w-[500px] mx-auto overflow-hidden rounded-sm shadow-[0_50px_100px_-20px_rgba(0,0,0,0.2)] transition-all duration-1000 group-hover:shadow-[0_80px_120px_-20px_rgba(0,0,0,0.3)] group-hover:-translate-y-3">
            <img src="{{ $shop->image_url }}" 
                 class="w-full h-full object-cover transition-transform duration-[3000ms] group-hover:scale-110" 
                 alt="Book Cover">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
          </div>
          
          <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 w-[80%] h-10 bg-slate-900/10 blur-3xl -z-10 group-hover:scale-110 transition-transform duration-1000"></div>
        </div>
      </div>

      <div class="lg:col-span-6 pt-10">
        <div class="space-y-12">
          
          <div class="space-y-4">
            <div class="flex items-center space-x-3">
               <span class="w-8 h-[1px] bg-gold"></span>
               <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">Elite Choice — {{ $shop->rating }}/5</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-serif text-slate-900 leading-[1.1]">
              {{ $shop->title }}
            </h1>
            <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">
              Authored by <span class="text-slate-900 underline decoration-gold/30 underline-offset-8">James Anderson</span>
            </p>
          </div>

          <div class="max-w-lg">
            <p class="text-lg text-slate-600 leading-relaxed font-serif italic">
              "{{ $shop->description }}"
            </p>
          </div>

          <div class="grid grid-cols-2 gap-y-8 gap-x-12 border-y border-slate-100 py-10">
            @foreach(['Category' => 'Self Development', 'Pages' => '320', 'Language' => 'English', 'Format' => 'Hardcover'] as $label => $value)
            <div class="space-y-1">
              <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">{{ $label }}</p>
              <p class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $value }}</p>
            </div>
            @endforeach
          </div>

          <div class="flex flex-col sm:flex-row items-center gap-8">
            <div class="flex flex-col">
               <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Investment</span>
               <span class="text-4xl font-light text-slate-900 tracking-tighter">$30.00</span>
            </div>

            <div class="flex-1 w-full grid grid-cols-2 gap-4">
              <button class="relative group overflow-hidden bg-slate-900 text-white py-5 rounded-full transition-all duration-500 hover:shadow-[0_20px_40px_rgba(0,0,0,0.2)]">
                <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.2em]">Secure Copy</span>
                <div class="absolute inset-0 bg-gold translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
              </button>

              <button class="group border border-slate-200 py-5 rounded-full hover:border-slate-900 transition-all duration-500">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-900 group-hover:tracking-[0.3em] transition-all">Add to Cart</span>
              </button>
            </div>
          </div>

          <div class="flex items-center space-x-2 text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">
            <svg class="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>Complimentary Global Shipping on Archive Orders</span>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
</x-layout.main-layout>