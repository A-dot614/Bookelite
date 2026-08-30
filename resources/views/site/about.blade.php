<x-layout.main-layout>
  <section class="relative py-32 px-6 bg-white overflow-hidden">
    <div class="absolute -top-10 left-10 text-[180px] font-black text-slate-50 select-none -z-10 opacity-40">EST. 2026</div>

    <div class="max-w-7xl mx-auto">
      <div class="grid lg:grid-cols-12 gap-16 items-center">
        
        <div class="lg:col-span-6 relative">
          <div class="relative z-10 overflow-hidden rounded-[2.5rem] shadow-2xl group cursor-none">
            <img src="https://picsum.photos/800/800" 
                 class="w-full h-[500px] object-cover transition-transform duration-1000 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-110" 
                 alt="The EliteBooks Experience">
            <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
          </div>
          
          <div class="absolute -bottom-10 -right-10 hidden md:block bg-accent p-8 rounded-3xl shadow-2xl z-20 max-w-[240px] animate-fade-in-up">
            <p class="text-white font-serif italic text-lg leading-tight mb-2">"Curation is the new luxury."</p>
            <span class="text-white/70 text-[10px] font-black uppercase tracking-widest">EliteBooks Studio</span>
          </div>

          <div class="absolute -top-8 -left-8 w-32 h-32 border-l-8 border-t-8 border-accent/20 rounded-tl-[3rem] -z-10"></div>
        </div>

        <div class="lg:col-span-6 space-y-10">
          <div class="space-y-4">
            <span class="text-[10px] font-black uppercase tracking-[0.5em] text-accent block">Our Philosophy</span>
            <h2 class="text-5xl md:text-7xl font-serif italic text-slate-900 leading-[1.1]">
              Elevating the <span class="font-sans not-italic font-black text-transparent bg-clip-text bg-gradient-to-br from-slate-900 via-slate-700 to-slate-500">Narrative.</span>
            </h2>
          </div>

          <div class="space-y-6 max-w-xl">
            <p class="text-xl text-slate-600 leading-relaxed font-medium">
              EliteBooks isn't just a marketplace; it is a <span class="text-slate-900 border-b-2 border-accent/30">digital sanctuary</span> for those who believe that what we read should be as beautiful as the space we inhabit.
            </p>
            <p class="text-sm text-slate-500 leading-loose tracking-wide">
              We specialize in the intersection of intellectual depth and aesthetic perfection. Every title in our collection undergoes a rigorous selection process, ensuring that only the most influential and visually stunning editions reach your library. From rare first-edition aesthetics to modern self-mastery guides, we redefine the standard of literary luxury.
            </p>
          </div>

          <div class="pt-6 flex flex-wrap gap-12 border-t border-slate-100">
            <div>
              <span class="block text-3xl font-black text-slate-900">12k+</span>
              <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Curated Titles</span>
            </div>
            <div>
              <span class="block text-3xl font-black text-slate-900">50+</span>
              <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Design Awards</span>
            </div>
          </div>

          <div class="pt-8">
            <button class="group relative overflow-hidden bg-slate-900 text-white px-10 py-5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] hover:bg-accent hover:shadow-2xl hover:shadow-accent/40 active:scale-95">
              <span class="relative z-10 text-xs font-black uppercase tracking-widest">Discover Our Mission</span>
            </button>
          </div>
        </div>

      </div>
    </div>
  </section>
</x-layout.main-layout>