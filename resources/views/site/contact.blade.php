<x-layout.main-layout>
<section class="relative py-40 px-6 bg-[#FCFCFC] overflow-hidden">
  <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-50/50 -skew-x-12 translate-x-1/3 -z-10"></div>
  <div class="absolute bottom-10 left-10 text-[200px] font-black text-slate-900/[0.01] select-none -z-10 tracking-tighter leading-none">
    CONTACT
  </div>

  <div class="max-w-7xl mx-auto">
    <div class="grid lg:grid-cols-12 gap-24 items-start">
      
      <div class="lg:col-span-5 space-y-16">
        <div class="space-y-6">
          <div class="flex items-center space-x-4">
            <span class="w-12 h-[1px] bg-gold"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-gold">Private Desk</span>
          </div>
          <h2 class="text-6xl md:text-8xl font-serif text-slate-900 leading-[0.9] tracking-tighter">
            Speak to the <br/>
            <span class="italic text-slate-400">Curators.</span>
          </h2>
          <p class="text-lg text-slate-500 font-light leading-relaxed max-w-sm">
            Available for private sourcing, restoration inquiries, and global collection logistics.
          </p>
        </div>

        <div class="space-y-12">
          <div class="group cursor-pointer">
            <p class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-300 mb-4 transition-colors group-hover:text-gold">Headquarters</p>
            <p class="text-xl font-serif text-slate-900">Karachi, PK — Studio 04</p>
            <div class="w-0 h-[1px] bg-gold mt-2 group-hover:w-48 transition-all duration-700"></div>
          </div>

          <div class="group cursor-pointer">
            <p class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-300 mb-4 transition-colors group-hover:text-gold">Digital Correspondence</p>
            <p class="text-xl font-serif text-slate-900">concierge@elitebooks.com</p>
            <div class="w-0 h-[1px] bg-gold mt-2 group-hover:w-48 transition-all duration-700"></div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-7 relative pt-12">
        <div class="relative bg-white p-12 md:p-20 shadow-[0_100px_80px_-50px_rgba(0,0,0,0.05)] border border-slate-50">
          
          <form action="#" class="space-y-12">
            <div class="grid md:grid-cols-2 gap-16">
              <div class="relative group">
                <input type="text" id="name" required
                       class="peer w-full bg-transparent py-4 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-colors placeholder-transparent">
                <label for="name" 
                       class="absolute left-0 top-0 text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[10px] peer-focus:font-black peer-focus:tracking-[0.3em] peer-focus:text-gold">
                  Full Identity
                </label>
              </div>

              <div class="relative group">
                <input type="email" id="email" required
                       class="peer w-full bg-transparent py-4 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-colors placeholder-transparent">
                <label for="email" 
                       class="absolute left-0 top-0 text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[10px] peer-focus:font-black peer-focus:tracking-[0.3em] peer-focus:text-gold">
                  Electronic Mail
                </label>
              </div>
            </div>

            <div class="relative group">
              <textarea id="message" rows="4" required
                        class="peer w-full bg-transparent py-4 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-colors placeholder-transparent resize-none"></textarea>
              <label for="message" 
                     class="absolute left-0 top-0 text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm peer-placeholder-shown:font-normal peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[10px] peer-focus:font-black peer-focus:tracking-[0.3em] peer-focus:text-gold">
                The Inquiry
              </label>
            </div>

            <div class="pt-8">
              <button type="submit" 
                      class="relative group overflow-hidden bg-slate-900 text-white px-16 py-6 transition-all duration-500 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)]">
                <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.5em]">Transmit Inquiry</span>
                <div class="absolute inset-0 bg-gold translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
              </button>
            </div>
          </form>

          <div class="absolute -top-6 -right-6 w-32 h-32 border-r border-t border-slate-100 -z-10"></div>
        </div>
      </div>

    </div>
  </div>
</section>
</x-layout.main-layout>