<x-layout.admin-layout>
  <section class="max-w-6xl mx-auto px-6 py-12">
    
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="space-y-2">
        <span class="text-[10px] font-black uppercase tracking-[0.5em] text-accent block">Archive Management</span>
        <h2 class="text-4xl md:text-5xl font-serif italic text-slate-900 leading-none">
          Edit <span class="font-sans not-italic font-black text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">Masterpiece.</span>
        </h2>
      </div>

      <a href="{{ route('admin.books.show', $ecommerce->slug) }}" 
         class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900 transition">
        ← Back to Details
      </a>
    </div>

    @if ($errors->any())
      <div class="mb-8 rounded-2xl bg-red-50 p-6 border border-red-200">
        <div class="flex items-center gap-3 text-red-800 font-bold text-sm mb-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>Please correct the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.books.update', $ecommerce->slug) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="grid lg:grid-cols-12 gap-12 items-start">
        
        <div class="lg:col-span-7 space-y-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
          
          <div class="grid md:grid-cols-2 gap-8">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Masterpiece Title</label>
              <input name="title" type="text" required value="{{ old('title', $ecommerce->title) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none placeholder:text-slate-200"
                     placeholder="The Great Gatsby">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Primary Author</label>
              <input name="author" type="text" required value="{{ old('author', $ecommerce->author) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none placeholder:text-slate-200"
                     placeholder="F. Scott Fitzgerald">
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-8">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Reference (ISBN)</label>
              <input name="isbn" type="text" value="{{ old('isbn', $ecommerce->isbn) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none placeholder:text-slate-200"
                     placeholder="978-3-16-148410-0">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Category</label>
              <select name="category" class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none appearance-none cursor-pointer">
                @foreach(['Philosophy', 'Architecture', 'Literature', 'Fine Art', 'Science', 'Self Development', 'General'] as $cat)
                  <option value="{{ $cat }}" {{ old('category', $ecommerce->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="grid md:grid-cols-3 gap-6">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Acquisition Value ($)</label>
              <input type="number" step="0.01" name="price" required value="{{ old('price', $ecommerce->price) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-serif italic font-bold text-xl focus:outline-none"
                     placeholder="0.00">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Stock Inventory</label>
              <input type="number" name="stock" value="{{ old('stock', $ecommerce->stock) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none"
                     placeholder="10">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Pages Count</label>
              <input type="number" name="pages" value="{{ old('pages', $ecommerce->pages) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none"
                     placeholder="320">
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-8">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Language</label>
              <input type="text" name="language" value="{{ old('language', $ecommerce->language ?? 'English') }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none">
            </div>

            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Rating (0.0 - 5.0)</label>
              <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $ecommerce->rating) }}"
                     class="w-full bg-transparent py-3 text-slate-900 font-bold focus:outline-none">
            </div>
          </div>

          <div class="space-y-4">
            <div class="relative group border-b border-slate-200 focus-within:border-accent transition-all duration-500">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">The Narrative (Description)</label>
              <textarea name="description" rows="5" required
                        class="w-full bg-transparent py-3 text-slate-600 font-medium focus:outline-none resize-none leading-relaxed"
                        placeholder="Detail the essence of this work...">{{ old('description', $ecommerce->description) }}</textarea>
            </div>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $ecommerce->is_active) ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-accent">
            <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">
              Active in Public Catalog
            </label>
          </div>

        </div>

        <div class="lg:col-span-5 space-y-6">
          <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100 shadow-inner group transition-all duration-700 hover:shadow-xl">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-6">Current Cover</label>
            
            <div class="relative h-[320px] w-full rounded-2xl overflow-hidden shadow-md mb-6 bg-white flex items-center justify-center">
              <img src="{{ $ecommerce->image_url }}" alt="{{ $ecommerce->title }}" class="w-full h-full object-cover">
            </div>

            <div class="relative h-[120px] w-full rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-2 bg-white transition-all hover:border-accent/40">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Replace Cover Image</p>
              <input type="file" name="cover" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
            </div>

            @if($ecommerce->images->isNotEmpty())
              <div class="mt-6 flex gap-3 flex-wrap">
                @foreach($ecommerce->images as $image)
                  <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->alt_text ?: $ecommerce->title }}" class="h-20 w-16 rounded-lg object-cover border border-slate-200">
                @endforeach
              </div>
            @endif

            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-3">Add Gallery Images (optional)</label>
              <input type="file" name="images[]" accept="image/*" multiple
                     class="w-full text-[11px] text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-5 file:py-2.5 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:text-white hover:file:bg-accent transition">
            </div>

            <div class="mt-8 flex flex-col gap-4">
              <button type="submit" 
                      class="w-full bg-slate-900 text-white py-5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-accent hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300">
                Update Masterpiece
              </button>
              
              <a href="{{ route('admin.books.index') }}" 
                 class="w-full py-4 text-center text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-slate-900 transition-colors">
                Cancel
              </a>
            </div>
          </div>
        </div>

      </div>
    </form>
  </section>
</x-layout.admin-layout>
