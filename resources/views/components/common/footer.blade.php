<footer class="relative bg-white pt-32 pb-12 overflow-hidden border-t border-slate-50">
  <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-slate-50 rounded-full blur-[120px] opacity-50"></div>
  <div class="absolute bottom-0 left-10 text-[15rem] font-black text-slate-50 leading-none select-none -z-10 tracking-tighter">
    ELITE
  </div>

  <div class="max-w-7xl mx-auto px-8 relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-24">
      
      <div class="lg:col-span-5">
        <h2 class="text-4xl font-serif italic text-slate-900 mb-8 tracking-tight">
          Curating the <span class="text-gold">extraordinary</span> since 2026.
        </h2>
        <div class="flex items-center space-x-4 group cursor-pointer">
          <div class="w-12 h-[1px] bg-slate-300 group-hover:w-20 group-hover:bg-gold transition-all duration-700"></div>
          <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 group-hover:text-slate-900 transition-colors">
            Our Philosophy
          </p>
        </div>
      </div>

      <div class="lg:col-span-4 grid grid-cols-2 gap-8">
        @foreach(['Explore' => ['Archives', 'Signature Series', 'Authors'], 'Legal' => ['Privacy', 'Terms', 'Returns']] as $title => $links)
        <div>
          <h3 class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-300 mb-8">{{ $title }}</h3>
          <ul class="space-y-4">
            @foreach($links as $link)
            <li>
              <a href="#" class="text-sm font-medium text-slate-600 hover:text-slate-900 hover:pl-2 transition-all duration-500 flex items-center group">
                <span class="w-0 h-0.5 bg-gold mr-0 group-hover:w-3 group-hover:mr-2 transition-all duration-500"></span>
                {{ $link }}
              </a>
            </li>
            @endforeach
          </ul>
        </div>
        @endforeach
      </div>

      <div class="lg:col-span-3 flex flex-col justify-between">
        <div>
          <h3 class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-300 mb-8">Connect</h3>
          <div class="flex flex-wrap gap-4">
            @foreach(['Instagram' => 'IG', 'Twitter' => 'TW', 'LinkedIn' => 'LI'] as $name => $short)
            <a href="#" title="{{ $name }}" class="relative w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center text-slate-900 text-[10px] font-bold overflow-hidden group">
              <span class="absolute inset-0 bg-slate-900 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-[cubic-bezier(0.19,1,0.22,1)]"></span>
              <span class="relative z-10 group-hover:text-white transition-colors duration-300">{{ $short }}</span>
            </a>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="flex items-center space-x-2">
        <span class="w-2 h-2 bg-gold rounded-full animate-pulse"></span>
        <p class="text-[9px] font-bold uppercase tracking-[0.3em] text-slate-400">
          System Status: Optimal
        </p>
      </div>
      
      <p class="text-[10px] font-medium text-slate-400 tracking-widest uppercase">
        &copy; 2026 <span class="text-slate-900 font-bold">Elite Archive</span> — All Rights Reserved.
      </p>

      <div class="flex space-x-6">
         <button class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-gold transition-colors">Back to Top ↑</button>
      </div>
    </div>
  </div>
</footer>