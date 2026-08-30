<x-layout.admin-layout>
  <section class="max-w-6xl mx-auto px-4 py-8 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <nav class="text-xs text-slate-400 font-medium" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2">
          <li><a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 transition">Dashboard</a></li>
          <li> / </li>
          <li><a href="{{ route('admin.books.index') }}" class="hover:text-slate-900 transition">Collection</a></li>
          <li> / </li>
          <li class="text-slate-900 font-bold truncate max-w-xs">{{ $ecommerce->title }}</li>
        </ol>
      </nav>

      <div class="flex items-center gap-3">
        <a href="{{ route('admin.books.edit', $ecommerce->slug) }}"
           class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-slate-700 hover:border-slate-900 hover:text-slate-900 transition shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Book
        </a>

        <form action="{{ route('admin.books.destroy', $ecommerce->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
          @csrf
          @method('DELETE')
          <button type="submit"
                  class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-red-600 hover:bg-red-600 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
          </button>
        </form>
      </div>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('status') }}
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

      <!-- Cover Section -->
      <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
        <div class="rounded-2xl overflow-hidden shadow-md aspect-[3/4] bg-slate-100">
          <img src="{{ $ecommerce->image_url }}" alt="{{ $ecommerce->title }} cover" class="w-full h-full object-cover">
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 text-xs">
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Category</p>
            <p class="font-bold text-slate-900 mt-1">{{ $ecommerce->category ?? 'General' }}</p>
          </div>
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Genre</p>
            <p class="font-bold text-slate-900 mt-1">{{ $ecommerce->genre ?? 'Literature' }}</p>
          </div>
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Language</p>
            <p class="font-bold text-slate-900 mt-1">{{ $ecommerce->language ?? 'English' }}</p>
          </div>
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pages</p>
            <p class="font-bold text-slate-900 mt-1">{{ $ecommerce->pages ?? 320 }} pages</p>
          </div>
        </div>
      </div>

      <!-- Details Section -->
      <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-8">
        <div>
          <div class="flex items-center gap-3 mb-3">
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $ecommerce->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
              {{ $ecommerce->is_active ? 'Public Catalog' : 'Draft' }}
            </span>
            <span class="text-xs text-slate-400">Added on {{ $ecommerce->created_at->format('M d, Y') }}</span>
          </div>

          <h1 class="text-3xl lg:text-4xl font-serif text-slate-900 font-bold leading-tight">{{ $ecommerce->title }}</h1>
          <p class="text-slate-500 font-medium mt-2">by <span class="text-slate-900 font-bold">{{ $ecommerce->author ?? 'Unknown Author' }}</span></p>
        </div>

        <div class="flex items-center gap-6 py-4 border-y border-slate-100">
          <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Price</p>
            <p class="text-3xl font-serif italic text-slate-900 font-bold">${{ number_format($ecommerce->price, 2) }}</p>
          </div>
          <div class="h-10 w-[1px] bg-slate-200"></div>
          <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Inventory</p>
            <p class="text-lg font-bold text-slate-900">{{ $ecommerce->stock }} copies</p>
          </div>
          <div class="h-10 w-[1px] bg-slate-200"></div>
          <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rating</p>
            <p class="text-lg font-bold text-amber-600 flex items-center gap-1">
              ★ {{ number_format($ecommerce->rating, 1) }} / 5.0
            </p>
          </div>
        </div>

        @if($ecommerce->isbn)
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center justify-between text-xs">
            <span class="font-black uppercase tracking-widest text-slate-400">ISBN Reference</span>
            <span class="font-mono font-bold text-slate-800">{{ $ecommerce->isbn }}</span>
          </div>
        @endif

        <div>
          <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Narrative Description</h3>
          <div class="text-sm text-slate-600 leading-relaxed space-y-4 font-light">
            {{ $ecommerce->description }}
          </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
          <a href="{{ route('detail', $ecommerce->slug) }}" target="_blank" 
             class="text-xs font-black uppercase tracking-widest text-slate-900 hover:text-accent flex items-center gap-2 transition">
            Preview on Public Store ↗
          </a>
        </div>
      </div>

    </div>
  </section>
</x-layout.admin-layout>