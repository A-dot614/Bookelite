<x-layout.admin-layout>
  <section class="max-w-6xl mx-auto px-6 py-12">
    
    <div class="mb-12 space-y-2">
      <span class="text-[10px] font-black uppercase tracking-[0.5em] text-accent block">Archive Management</span>
      <h2 class="text-4xl md:text-5xl font-serif italic text-slate-900 leading-none">
        New <span class="font-sans not-italic font-black text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">Acquisition.</span>
      </h2>
    </div>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="grid lg:grid-cols-12 gap-12 items-start">
        
        <div class="lg:col-span-7 space-y-8">
          
          <div class="grid md:grid-cols-2 gap-8">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Masterpiece Title</label>
              <input name="title" type="text" required
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none placeholder:text-slate-200"
                     placeholder="The Great Gatsby">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Primary Author</label>
              <input name="author" type="text" required
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none placeholder:text-slate-200"
                     placeholder="F. Scott Fitzgerald">
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-8">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Reference (ISBN)</label>
              <input name="isbn" type="text"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none placeholder:text-slate-200"
                     placeholder="978-3-16-148410-0">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Collection Genre</label>
              <select name="genre" class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none appearance-none cursor-pointer">
                <option>Fiction</option>
                <option>Non-Fiction</option>
                <option>Fine Art</option>
                <option>Biography</option>
                <option>Self-mastery</option>
              </select>
            </div>
          </div>

          <div class="space-y-8">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Acquisition Value ($)</label>
              <input type="number" name="price" required
                     class="w-full bg-transparent py-3 text-slate-900 font-serif italic font-bold text-xl focus:outline-none"
                     placeholder="0.00">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">The Narrative (Description)</label>
              <textarea name="description" rows="4" 
                        class="w-full bg-transparent py-3 text-slate-600 font-medium focus:outline-none resize-none leading-relaxed"
                        placeholder="Detail the essence of this work..."></textarea>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="bg-slate-50 rounded-[3rem] p-8 border border-slate-100 shadow-inner group transition-all duration-700 hover:shadow-2xl">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-6">Cover Aesthetics</label>
            
            <div class="relative h-[400px] w-full rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-4 bg-white transition-all group-hover:border-accent/40">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200 group-hover:text-accent transition-colors duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Drop Masterpiece Image</p>
              
              <input type="file" name="cover" accept="image/*" 
                     class="absolute inset-0 opacity-0 cursor-pointer">
            </div>

            <div class="mt-12 flex flex-col gap-4">
              <button type="submit" 
                      class="w-full bg-slate-900 text-white py-5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-accent hover:shadow-2xl hover:shadow-accent/40 hover:-translate-y-1 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
                Finalize Entry
              </button>
              
              <button type="reset" 
                      class="w-full py-5 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-slate-900 transition-colors">
                Discard Changes
              </button>
            </div>
          </div>
        </div>

      </div>
    </form>
  </section>
</x-layout.admin-layout>