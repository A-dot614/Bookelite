<x-layout.main-layout>
<section class="py-40 px-6 bg-[#fafafa] overflow-hidden relative">
  <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full text-center pointer-events-none select-none -z-10">
    <span class="text-[20vw] font-black text-slate-900/[0.02] leading-none tracking-tighter">PROTOCOLS</span>
  </div>

  <div class="max-w-7xl mx-auto">
    <div class="relative mb-32 flex flex-col items-center">
      <div class="flex items-center space-x-4 mb-6">
        <span class="w-12 h-[1px] bg-gold"></span>
        <span class="text-[10px] font-black uppercase tracking-[0.5em] text-gold">The Signature Suite</span>
        <span class="w-12 h-[1px] bg-gold"></span>
      </div>
      
      <h2 class="text-6xl md:text-8xl font-serif text-slate-900 text-center leading-[0.85] tracking-tighter">
        The Art of <br/> <span class="italic text-slate-400">Execution.</span>
      </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-8">
      
      @foreach([
        ['01', 'Swift Protocol', 'Proprietary logistics ensuring your literary collection arrives in pristine condition within 48 hours.', 'M13 10V3L4 14h7v7l9-11h-7z'],
        ['02', 'Pure Curation', 'We source historical artifacts and modern masterpieces for the discerning reader.', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253'],
        ['03', 'Elite Vault', 'Encrypted transactions and private data protection for our high-profile members.', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944']
      ] as $service)
      <div class="group relative">
        <div class="relative z-10 h-full bg-white border border-slate-100 p-12 transition-all duration-1000 ease-[cubic-bezier(0.19,1,0.22,1)] group-hover:-translate-y-6 group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.1)] group-hover:border-transparent">
          
          <div class="absolute top-0 right-0 w-32 h-32 bg-gold/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-gold/10 transition-all duration-700"></div>

          <div class="flex justify-between items-start mb-16">
            <div class="w-16 h-16 rounded-full border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 group-hover:border-slate-900 transition-all duration-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400 group-hover:text-gold transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $service[3] }}" />
              </svg>
            </div>
            <span class="text-5xl font-light text-slate-100 group-hover:text-slate-900/5 transition-colors duration-700 italic font-serif leading-none">{{ $service[0] }}</span>
          </div>

          <h3 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight group-hover:text-gold transition-colors duration-500">{{ $service[1] }}</h3>
          <p class="text-slate-500 leading-relaxed font-light text-sm mb-12">
            {{ $service[2] }}
          </p>
          
          <div class="relative pt-6">
            <div class="h-[1px] bg-slate-100 w-full relative overflow-hidden mb-6">
               <div class="absolute inset-0 bg-gold -translate-x-full group-hover:translate-x-0 transition-transform duration-700 ease-in-out"></div>
            </div>
            <a href="#" class="inline-flex items-center text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-all duration-500">
              Inquire Details <span class="ml-2 opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all duration-500">→</span>
            </a>
          </div>
        </div>

        <div class="absolute inset-0 bg-gold/20 blur-3xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-1000 -z-10"></div>
      </div>
      @endforeach

    </div>
  </div>
</section>
</x-layout.main-layout>