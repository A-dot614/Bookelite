<x-layout.main-layout>
  <style>
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInRight {
      from { opacity: 0; transform: translateX(50px); }
      to { opacity: 1; transform: translateX(0); }
    }
    .reveal { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .reveal-delay-1 { animation-delay: 0.2s; }
    .reveal-delay-2 { animation-delay: 0.4s; }
  </style>
<section class="relative min-h-[90vh] flex items-center bg-[#fafafa] overflow-hidden pt-20">
  <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden">
    <span class="text-[28vw] font-black text-slate-900/[0.02] leading-none tracking-tighter">ELITE</span>
  </div>

  <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
    <div class="grid lg:grid-cols-12 gap-16 items-center">
      
      <div class="lg:col-span-7 space-y-10 reveal">
        <div class="flex items-center space-x-4">
          <span class="w-12 h-[1px] bg-gold"></span>
          <span class="text-[10px] font-black uppercase tracking-[0.5em] text-gold">Volume 01 — 2026</span>
        </div>
        
        <h1 class="text-6xl md:text-9xl font-serif text-slate-900 leading-[0.85] tracking-tighter">
          Read with <br/>
          <span class="italic text-slate-400">Distinction.</span>
        </h1>
        
        <div class="max-w-md space-y-8">
          <p class="text-xl text-slate-500 leading-relaxed font-light reveal-delay-1">
            Curating the intersection of intellectual depth and <span class="text-slate-900 font-medium">aesthetic perfection.</span> A sanctuary for the modern reader.
          </p>
          
          <div class="flex flex-wrap gap-8 reveal-delay-2 pt-4">
            <button class="group relative py-4 pr-16 text-xs font-black uppercase tracking-[0.3em] text-slate-900 transition-all">
              Explore the Archive
              <div class="absolute right-0 top-1/2 -translate-y-1/2 w-12 h-12 bg-slate-900 rounded-full flex items-center justify-center group-hover:w-full transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]">
                 <svg class="w-4 h-4 text-white group-hover:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
              </div>
            </button>
          </div>
        </div>
      </div>

      <div class="lg:col-span-5 relative hidden lg:flex h-[600px] items-center justify-center">
        <div class="absolute bottom-20 w-64 h-10 bg-slate-900/10 blur-3xl rounded-full"></div>
        
        <div class="relative w-72 h-[450px] group transition-all duration-1000 [perspective:1000px]">
          
          <div class="absolute inset-0 bg-slate-200 rounded-sm rotate-[-12deg] translate-x-[-20px] opacity-40 blur-[1px] transition-transform duration-1000 group-hover:rotate-[-15deg]"></div>
          
          <div class="absolute inset-0 bg-white border border-slate-100 rounded-sm rotate-[-6deg] shadow-xl transition-transform duration-1000 group-hover:rotate-[-8deg] group-hover:-translate-y-4"></div>
          
          <div class="absolute inset-0 bg-white rounded-sm rotate-[4deg] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.2)] flex flex-col items-center justify-center p-12 text-center transition-all duration-1000 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:rotate-0 group-hover:scale-105 border border-slate-50">
            <div class="mb-10 w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-gold text-xs">★</div>
            <p class="font-serif italic text-2xl text-slate-800 leading-snug">
              "Words for the <br/> extraordinary."
            </p>
            <div class="mt-10 w-12 h-[1px] bg-gold/30"></div>
            <p class="mt-4 text-[9px] font-bold uppercase tracking-[0.4em] text-slate-300">Archive No. 42</p>
          </div>

          <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-sm opacity-0 group-hover:opacity-100 transition-opacity duration-1000">
            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/40 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-[2000ms]"></div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

  <section class="bg-[#fcfcfc] py-24 px-6 lg:px-12">
    <div class="max-w-7xl mx-auto">
      <div class="flex flex-col md:flex-row justify-between items-center mb-16 gap-4 reveal">
        <h2 class="text-4xl font-bold text-slate-900 tracking-tight text-center md:text-left">
          Featured <span class="text-accent italic font-serif">Masterpieces</span>
        </h2>
        <div class="flex gap-4">
          <span class="text-sm font-medium text-slate-400">Showing all {{ $ecommerces->count() }} titles</span>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
@foreach($ecommerces as $item)
<article class="group relative reveal">
    <div class="relative aspect-[3/4] overflow-hidden bg-[#f1f1f1] transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.15)] group-hover:-translate-y-3">
        
        <div class="absolute top-4 left-4 z-20">
            <span class="text-[8px] font-black uppercase tracking-[0.3em] text-white bg-slate-900/20 backdrop-blur-md px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                Archive No. {{ $loop->iteration }}
            </span>
        </div>

        <img 
            src="{{ $item->image_url }}" 
            class="w-full h-full object-cover transition-transform duration-[1.8s] ease-out group-hover:scale-110 group-hover:rotate-1" 
            alt="{{ $item->title }}"
            loading="lazy"
        >

        <div class="absolute bottom-6 right-6 z-20 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 delay-75">
            <div class="bg-white/10 backdrop-blur-2xl border border-white/20 p-4 min-w-[80px] text-center shadow-2xl">
                <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mb-1">Price</p>
                <p class="text-white font-serif italic text-lg leading-none">${{ $item->price }}</p>
            </div>
        </div>

        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
             <a href="{{ route('detail', $item->slug) }}" class="relative overflow-hidden bg-white px-8 py-3 group/btn transition-transform active:scale-95">
                <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.2em] text-slate-900">View Artifact</span>
                <div class="absolute inset-0 bg-gold -translate-x-full group-hover/btn:translate-x-0 transition-transform duration-500"></div>
             </a>
        </div>
    </div>

    <div class="mt-8 relative">
        <div class="flex items-center space-x-2 mb-3">
            <div class="flex space-x-0.5 text-[8px] text-gold">
                @for($i = 0; $i < 5; $i++)
                    <span>{{ $i < $item->rating ? '●' : '○' }}</span>
                @endfor
            </div>
            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">/ Premium Stock</span>
        </div>

        <h3 class="text-xl font-serif text-slate-900 leading-tight group-hover:text-gold transition-colors duration-500">
            {{ $item->title }}
        </h3>
        
        <p class="mt-3 text-xs text-slate-500 line-clamp-2 leading-relaxed font-light tracking-wide max-w-[90%]">
            {{ $item->description }}
        </p>

        <div class="mt-6 h-[1px] bg-slate-100 w-full relative overflow-hidden">
            <div class="absolute inset-0 bg-gold -translate-x-full group-hover:translate-x-0 transition-transform duration-700 ease-in-out"></div>
        </div>
        
        <button class="absolute -top-2 right-0 w-8 h-8 flex items-center justify-center group/add">
            <div class="relative w-4 h-4">
                <div class="absolute top-1/2 left-0 w-full h-[1px] bg-slate-300 group-hover/add:bg-gold transition-colors"></div>
                <div class="absolute top-0 left-1/2 w-[1px] h-full bg-slate-300 group-hover/add:bg-gold group-hover/add:rotate-90 transition-all duration-500"></div>
            </div>
        </button>
    </div>
</article>
@endforeach
      </div>
    </div>
  </section>
</x-layout.main-layout>