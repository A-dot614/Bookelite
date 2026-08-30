<x-layout.main-layout>
  <style>
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .reveal { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
    .reveal-delay-1 { animation-delay: 0.15s; }
    .reveal-delay-2 { animation-delay: 0.3s; }
  </style>

  <!-- ================= HERO SECTION ================= -->
  <section class="relative min-h-[85vh] flex items-center bg-[#fafafa] overflow-hidden pt-28 pb-16">
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden">
      <span class="text-[28vw] font-black text-slate-900/[0.02] leading-none tracking-tighter">ARCHIVE</span>
    </div>

    <div class="max-w-7xl mx-auto px-6 w-full relative z-10">
      <div class="grid lg:grid-cols-12 gap-16 items-center">
        
        <div class="lg:col-span-7 space-y-8 reveal">
          <div class="flex items-center space-x-4">
            <span class="w-12 h-[1px] bg-gold"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.5em] text-gold">Volume 01 — 2026</span>
          </div>
          
          <h1 class="text-5xl sm:text-7xl lg:text-8xl font-serif text-slate-900 leading-[0.9] tracking-tight">
            Read with <br/>
            <span class="italic text-slate-400 font-normal">Distinction.</span>
          </h1>
          
          <div class="max-w-lg space-y-6">
            <p class="text-lg text-slate-500 leading-relaxed font-light reveal-delay-1">
              Curating the intersection of intellectual depth and <span class="text-slate-900 font-medium">aesthetic perfection.</span> A sanctuary for the modern reader and serious collector.
            </p>
            
            <div class="flex flex-wrap items-center gap-4 reveal-delay-2 pt-2">
              <a href="#collection" class="inline-flex items-center gap-3 bg-[#141414] text-white px-8 py-4 rounded-full text-xs font-black uppercase tracking-[0.25em] hover:bg-slate-800 transition shadow-md">
                <span>Explore Catalog</span>
                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
              </a>
              
              <a href="{{ route('about') }}" class="inline-flex items-center gap-2 border border-slate-200 bg-white text-slate-700 px-6 py-4 rounded-full text-xs font-bold uppercase tracking-widest hover:border-slate-900 transition">
                Our Philosophy
              </a>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5 relative hidden lg:flex h-[520px] items-center justify-center">
          <div class="absolute bottom-10 w-64 h-12 bg-slate-900/10 blur-3xl rounded-full"></div>
          
          <div class="relative w-72 h-[420px] group transition-all duration-1000 [perspective:1000px]">
            <div class="absolute inset-0 bg-slate-200 rounded-2xl rotate-[-10deg] translate-x-[-15px] opacity-40 blur-[1px]"></div>
            <div class="absolute inset-0 bg-white border border-slate-100 rounded-2xl rotate-[-4deg] shadow-xl"></div>
            
            <div class="absolute inset-0 bg-white rounded-2xl rotate-[3deg] shadow-[0_40px_80px_-20px_rgba(0,0,0,0.15)] flex flex-col items-center justify-center p-10 text-center transition-all duration-700 group-hover:rotate-0 border border-slate-100">
              <div class="mb-8 w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center text-gold text-base">★</div>
              <p class="font-serif italic text-2xl text-slate-800 leading-snug">
                "Words for the <br/> extraordinary."
              </p>
              <div class="mt-8 w-10 h-[1px] bg-gold/40"></div>
              <p class="mt-4 text-[9px] font-black uppercase tracking-[0.4em] text-slate-400">Archive Edition No. 01</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ================= CATALOG SECTION ================= -->
  <section id="collection" class="bg-[#fcfcfc] py-20 px-6 lg:px-12 border-t border-slate-100">
    <div class="max-w-7xl mx-auto space-y-12">
      
      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 reveal">
        <div>
          <div class="flex items-center space-x-3 mb-2">
            <span class="w-6 h-[1px] bg-gold"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-gold">Archival Library</span>
          </div>
          <h2 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 tracking-tight">
            Featured <span class="italic font-normal text-slate-400">Masterpieces.</span>
          </h2>
        </div>

        <div class="text-xs text-slate-400 font-medium">
          Displaying {{ $ecommerces->total() }} curated works
        </div>
      </div>

      <!-- Search & Filters Container (Mobbin Stadium Controls) -->
      <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-6">
        <form method="GET" action="{{ route('home') }}#collection" class="space-y-4">
          
          <!-- Top Row: Search input + Sort dropdown -->
          <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
              <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title, author, description, or ISBN..."
                     class="w-full pl-11 pr-4 py-3.5 bg-[#f0f0f0] rounded-full text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition">
            </div>

            <!-- Sort Dropdown -->
            <div class="w-full md:w-56">
              <select name="sort" onchange="this.form.submit()"
                      class="w-full bg-[#f0f0f0] rounded-full px-5 py-3.5 text-xs font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition appearance-none cursor-pointer">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort: Newest First</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating: Highest First</option>
                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Alphabetical (A-Z)</option>
              </select>
            </div>

            <button type="submit" class="bg-[#141414] text-white px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-wider hover:bg-slate-800 transition">
              Apply
            </button>
          </div>

          <!-- Bottom Row: Category Chips + Price Range -->
          <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-50">
            
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
              <a href="{{ route('home', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}#collection"
                 class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider transition {{ !request('category') || request('category') === 'all' ? 'bg-[#141414] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Categories
              </a>

              @foreach($categories as $cat)
                <a href="{{ route('home', array_merge(request()->except('category', 'page'), ['category' => $cat])) }}#collection"
                   class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider transition {{ request('category') === $cat ? 'bg-[#141414] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                  {{ $cat }}
                </a>
              @endforeach
            </div>

            <!-- Price Filters & Reset -->
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-2 text-xs">
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min $"
                       class="w-20 bg-[#f0f0f0] rounded-full px-3 py-1.5 text-xs text-slate-900 text-center focus:bg-white focus:outline-none focus:ring-1 focus:ring-slate-900">
                <span class="text-slate-400">–</span>
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max $"
                       class="w-20 bg-[#f0f0f0] rounded-full px-3 py-1.5 text-xs text-slate-900 text-center focus:bg-white focus:outline-none focus:ring-1 focus:ring-slate-900">
              </div>

              @if(request()->anyFilled(['q', 'category', 'min_price', 'max_price', 'sort']))
                <a href="{{ route('home') }}#collection" class="text-xs text-slate-400 hover:text-red-600 transition underline">
                  Reset
                </a>
              @endif
            </div>

          </div>

        </form>
      </div>

      <!-- ================= BOOKS GRID ================= -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($ecommerces as $item)
          <article class="group relative bg-white rounded-3xl border border-slate-100 p-5 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-500 flex flex-col justify-between">
            
            <div>
              <!-- Cover Image Box -->
              <div class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-[#f1f1f1] mb-6">
                
                <div class="absolute top-3 left-3 z-20">
                  <span class="text-[9px] font-black uppercase tracking-widest text-slate-900 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full shadow-sm">
                    {{ $item->category ?? 'General' }}
                  </span>
                </div>

                <!-- Wishlist heart button -->
                <div class="absolute top-3 right-3 z-20">
                  @auth
                    <form action="{{ route('wishlist.toggle', $item->slug) }}" method="POST">
                      @csrf
                      <button type="submit" 
                              class="w-9 h-9 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-slate-700 hover:text-red-600 transition shadow-sm"
                              title="Toggle Wishlist">
                        <svg class="w-4 h-4 {{ auth()->user()->hasInWishlist($item->id) ? 'fill-red-500 text-red-500' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                      </button>
                    </form>
                  @else
                    <a href="{{ route('login') }}" 
                       class="w-9 h-9 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-slate-400 hover:text-slate-900 transition shadow-sm">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </a>
                  @endauth
                </div>

                <a href="{{ route('detail', $item->slug) }}">
                  <img src="{{ $item->image_url }}" 
                       class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" 
                       alt="{{ $item->title }}"
                       loading="lazy">
                </a>

                <!-- Hover Quick View Scrim -->
                <div class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none flex items-center justify-center">
                  <span class="bg-white text-slate-900 px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl">
                    View Masterpiece
                  </span>
                </div>
              </div>

              <!-- Metadata & Details -->
              <div class="space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <div class="flex items-center text-amber-500 font-bold gap-1 text-[11px]">
                    <span>★</span>
                    <span>{{ number_format($item->rating, 1) }}</span>
                  </div>
                  <span class="text-[10px] uppercase tracking-wider font-semibold text-slate-400">
                    {{ $item->stock > 0 ? $item->stock.' in stock' : 'Out of stock' }}
                  </span>
                </div>

                <h3 class="text-lg font-serif font-bold text-slate-900 leading-tight group-hover:text-gold transition line-clamp-1">
                  <a href="{{ route('detail', $item->slug) }}">{{ $item->title }}</a>
                </h3>

                <p class="text-xs text-slate-400 font-medium">
                  by <span class="text-slate-700">{{ $item->author ?? 'Unknown Author' }}</span>
                </p>

                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-light pt-1">
                  {{ $item->description }}
                </p>
              </div>
            </div>

            <!-- Footer Price & Add to Bag -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
              <div>
                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Price</span>
                <span class="text-xl font-serif font-bold text-slate-900">${{ number_format($item->price, 2) }}</span>
              </div>

              @if($item->stock > 0)
                <form action="{{ route('cart.add', $item->slug) }}" method="POST">
                  @csrf
                  <input type="hidden" name="quantity" value="1">
                  <button type="submit" 
                          class="inline-flex items-center gap-2 bg-[#141414] text-white px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-sm">
                    <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Add
                  </button>
                </form>
              @else
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sold Out</span>
              @endif
            </div>

          </article>
        @empty
          <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100 p-12">
            <p class="text-base font-serif font-bold text-slate-900">No masterpieces found matching your search.</p>
            <p class="text-xs text-slate-400 mt-2">Try clearing search filters or searching with different terms.</p>
            <a href="{{ route('home') }}#collection" class="mt-6 inline-block bg-[#141414] text-white px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest">
              Reset Filters
            </a>
          </div>
        @endforelse
      </div>

      <!-- ================= PAGINATION ================= -->
      @if($ecommerces->hasPages())
        <div class="pt-8 flex justify-center">
          <div class="bg-white rounded-full border border-slate-200 p-2 shadow-sm">
            {{ $ecommerces->links() }}
          </div>
        </div>
      @endif

    </div>
  </section>

</x-layout.main-layout>