<x-layout.seller-layout>
  <section class="max-w-5xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Merchant Inventory</p>
        <h2 class="text-3xl font-serif font-bold text-slate-900 mt-1">Edit Listing: {{ $ecommerce->title }}</h2>
      </div>

      <a href="{{ route('seller.books.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition">
        ← Back to Catalog
      </a>
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

    <form action="{{ route('seller.books.update', $ecommerce->slug) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="grid lg:grid-cols-12 gap-8 items-start">
        
        <!-- Main Form Fields -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
          
          <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Book Title</label>
              <input type="text" name="title" required value="{{ old('title', $ecommerce->title) }}"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>

            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Author</label>
              <input type="text" name="author" required value="{{ old('author', $ecommerce->author) }}"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Category</label>
              <select name="category" class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
                @foreach(['Philosophy', 'Architecture', 'Literature', 'Fine Art', 'Science', 'Self Development', 'General'] as $cat)
                  <option value="{{ $cat }}" {{ old('category', $ecommerce->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">ISBN Reference</label>
              <input type="text" name="isbn" value="{{ old('isbn', $ecommerce->isbn) }}"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>
          </div>

          <div class="grid md:grid-cols-3 gap-6">
            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Price ($ USD)</label>
              <input type="number" step="0.01" name="price" required value="{{ old('price', $ecommerce->price) }}"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>

            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Stock Inventory</label>
              <input type="number" name="stock" value="{{ old('stock', $ecommerce->stock) }}"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>

            <div class="space-y-2">
              <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Pages Count</label>
              <input type="number" name="pages" value="{{ old('pages', $ecommerce->pages) }}"
                     class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Narrative Description</label>
            <textarea name="description" rows="5" required
                      class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition resize-none">{{ old('description', $ecommerce->description) }}</textarea>
          </div>

          <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $ecommerce->is_active) ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
            <label for="is_active" class="text-sm font-semibold text-slate-700 cursor-pointer">
              Active in Public Storefront
            </label>
          </div>

        </div>

        <!-- Right: Image Preview, Upload & Submit -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-6">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 block">Current Cover</label>
          
          <div class="relative h-64 w-full rounded-2xl overflow-hidden shadow-sm bg-slate-100 mb-4">
            <img src="{{ $ecommerce->image_url }}" alt="{{ $ecommerce->title }}" class="w-full h-full object-cover">
          </div>

          <div class="relative h-28 w-full rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-2 bg-slate-50 hover:border-slate-400 transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Replace Cover Image</p>
            <input type="file" name="cover" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
          </div>

          @if($ecommerce->images->isNotEmpty())
            <div class="flex gap-2 flex-wrap">
              @foreach($ecommerce->images as $image)
                <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $image->alt_text ?: $ecommerce->title }}" class="h-16 w-12 rounded-lg object-cover border border-slate-200">
              @endforeach
            </div>
          @endif

          <div class="rounded-2xl border border-slate-200 p-4">
            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-2">Add Gallery Images</label>
            <input type="file" name="images[]" accept="image/*" multiple
                   class="w-full text-[11px] text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:text-white hover:file:bg-slate-700 transition">
          </div>

          <button type="submit" 
                  class="w-full bg-[#141414] text-white py-4 rounded-full text-xs font-black uppercase tracking-[0.25em] hover:bg-slate-800 transition shadow-md">
            Save Modifications
          </button>
        </div>

      </div>
    </form>
  </section>
</x-layout.seller-layout>
