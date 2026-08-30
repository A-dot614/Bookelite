<x-layout.main-layout>
<section class="relative py-24 px-6 bg-[#fafafa] overflow-hidden min-h-screen flex items-center">
  <div class="absolute top-0 left-0 w-full h-full text-[25vw] font-black text-slate-900/[0.01] select-none -z-10 leading-none flex items-center justify-center">
    EDITION
  </div>

  <div class="max-w-7xl mx-auto w-full">
    
    @if (session('status'))
      <div class="mb-12 rounded-full bg-slate-900 text-white px-8 py-4 text-xs font-bold uppercase tracking-widest flex items-center justify-between shadow-2xl">
        <span class="flex items-center gap-3">
          <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
          {{ session('status') }}
        </span>
        <a href="{{ route('cart.index') }}" class="text-gold underline hover:text-white transition">View Bag →</a>
      </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-16 lg:gap-20 items-start">
      
      <!-- Book Cover Presentation -->
      <div class="lg:col-span-6 lg:sticky lg:top-32">
        <div class="relative group">
          <div class="relative z-10 aspect-[3/4] max-w-[500px] mx-auto overflow-hidden rounded-2xl shadow-[0_50px_100px_-20px_rgba(0,0,0,0.18)] transition-all duration-1000 group-hover:shadow-[0_80px_120px_-20px_rgba(0,0,0,0.25)] group-hover:-translate-y-2 bg-[#f1f1f1]">
            <img src="{{ $ecommerce->image_url }}" 
                 class="w-full h-full object-cover transition-transform duration-[2000ms] group-hover:scale-105" 
                 alt="{{ $ecommerce->title }} Cover">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
          </div>
          
          <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 w-[80%] h-10 bg-slate-900/10 blur-3xl -z-10 group-hover:scale-110 transition-transform duration-1000"></div>
        </div>
      </div>

      <!-- Book Narrative & Purchase Actions -->
      <div class="lg:col-span-6 pt-4 lg:pt-10">
        <div class="space-y-10">
          
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <span class="w-8 h-[1px] bg-gold"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">
                  Archive Rating — {{ number_format($ecommerce->rating, 1) }} / 5.0
                </span>
              </div>

              @auth
                <form action="{{ route('wishlist.toggle', $ecommerce->slug) }}" method="POST">
                  @csrf
                  <button type="submit" 
                          class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:border-slate-900 transition shadow-sm"
                          title="Save to Wishlist">
                    <svg class="w-4 h-4 {{ auth()->user()->hasInWishlist($ecommerce->id) ? 'fill-red-500 text-red-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>{{ auth()->user()->hasInWishlist($ecommerce->id) ? 'Saved' : 'Wishlist' }}</span>
                  </button>
                </form>
              @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-400 hover:text-slate-900 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                  <span>Wishlist</span>
                </a>
              @endauth
            </div>

            <h1 class="text-4xl md:text-6xl font-serif text-slate-900 leading-[1.1] tracking-tight">
              {{ $ecommerce->title }}
            </h1>
            
            <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">
              Authored by <span class="text-slate-900 font-bold underline decoration-gold/30 underline-offset-8">{{ $ecommerce->author ?? 'Unknown Author' }}</span>
            </p>
          </div>

          <div class="max-w-xl">
            <p class="text-lg text-slate-600 leading-relaxed font-serif italic">
              "{{ $ecommerce->description }}"
            </p>
          </div>

          <!-- Metadata Spec Grid -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 border-y border-slate-200 py-8">
            <div class="space-y-1">
              <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">Category</p>
              <p class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $ecommerce->category ?? 'General' }}</p>
            </div>

            <div class="space-y-1">
              <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">Pages</p>
              <p class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $ecommerce->pages ?? 320 }}</p>
            </div>

            <div class="space-y-1">
              <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">Language</p>
              <p class="text-xs font-black text-slate-900 uppercase tracking-widest">{{ $ecommerce->language ?? 'English' }}</p>
            </div>

            <div class="space-y-1">
              <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-slate-400">Status</p>
              <p class="text-xs font-black {{ $ecommerce->stock > 0 ? 'text-emerald-700' : 'text-red-600' }} uppercase tracking-widest">
                {{ $ecommerce->stock > 0 ? 'In Stock ('.$ecommerce->stock.')' : 'Archived Out' }}
              </p>
            </div>
          </div>

          <!-- Price & Purchase Action -->
          <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
            <div class="flex items-baseline justify-between">
              <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Acquisition Value</span>
                <span class="text-4xl font-serif text-slate-900 font-bold">${{ number_format($ecommerce->price, 2) }}</span>
              </div>
              
              @if($ecommerce->isbn)
                <span class="text-[10px] text-slate-400 font-mono">ISBN: {{ $ecommerce->isbn }}</span>
              @endif
            </div>

            @if($ecommerce->stock > 0)
              <form action="{{ route('cart.add', $ecommerce->slug) }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex items-center gap-4">
                  <div class="w-32 flex items-center border border-slate-200 rounded-full bg-[#f0f0f0] px-4 py-3">
                    <label for="quantity" class="text-[9px] font-black uppercase tracking-wider text-slate-400 mr-2">Qty</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $ecommerce->stock }}"
                           class="w-full bg-transparent text-sm font-black text-slate-900 focus:outline-none text-center">
                  </div>

                  <button type="submit" 
                          class="flex-1 relative group overflow-hidden bg-[#141414] text-white py-4 px-8 rounded-full text-xs font-black uppercase tracking-[0.25em] transition-all hover:bg-slate-800 shadow-md">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                      <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                      Acquire to Collection
                    </span>
                  </button>
                </div>
              </form>
            @else
              <div class="rounded-full bg-slate-100 p-4 text-center text-xs font-black uppercase tracking-widest text-slate-500">
                Currently Out of Print
              </div>
            @endif
          </div>

          <div class="flex items-center space-x-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] pt-2">
            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>Complimentary Insured Global Dispatch on All Artifacts</span>
          </div>

        </div>
      </div>

    </div>

    <!-- Reviews Section -->
    <div class="max-w-4xl mx-auto mt-28">
      <div class="flex items-center space-x-3 mb-4">
        <span class="w-8 h-[1px] bg-gold"></span>
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">Patron Reviews</span>
      </div>
      <h2 class="text-3xl md:text-4xl font-serif text-slate-900 font-bold tracking-tight">
        Critical <span class="italic font-normal text-slate-400">Responses.</span>
      </h2>

      @if($ecommerce->approved_reviews_count > 0)
        <div class="mt-8 space-y-4">
          @foreach($ecommerce->approvedReviews as $review)
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                  <span class="w-9 h-9 rounded-full bg-slate-900 text-white text-xs font-black flex items-center justify-center uppercase">
                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                  </span>
                  <div>
                    <p class="text-sm font-bold text-slate-900">{{ $review->user->name ?? 'Patron' }}</p>
                    <p class="text-[10px] text-slate-400">{{ $review->created_at->format('M d, Y') }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-0.5">
                  @for($i = 1; $i <= 5; $i++)
                    <span class="text-sm {{ $i <= $review->rating ? 'text-gold' : 'text-slate-200' }}">★</span>
                  @endfor
                </div>
              </div>
              <p class="text-sm text-slate-600 leading-relaxed italic">{{ $review->body }}</p>
            </div>
          @endforeach
        </div>
      @else
        <p class="text-sm text-slate-500 font-light mt-6">No verified reviews published yet. Be the first to share your thoughts.</p>
      @endif

      @auth
        @can('create-review', $ecommerce)
          <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm mt-8">
            <h3 class="text-base font-serif font-bold text-slate-900 mb-6">Share your review</h3>
            <form action="{{ route('review.store', $ecommerce->slug) }}" method="POST" class="space-y-5" x-data="{ rating: 0 }">
              @csrf
              <div class="flex items-center gap-1">
                <span class="text-xs font-bold text-slate-500 mr-3">Your rating:</span>
                @for($i = 1; $i <= 5; $i++)
                  <button type="button" @click="rating = {{ $i }}"
                          class="text-2xl transition" :class="rating >= {{ $i }} ? 'text-gold' : 'text-slate-200'">
                    ★
                  </button>
                @endfor
              </div>
              <input type="hidden" name="rating" :value="rating">

              <div>
                <textarea name="comment" rows="3" required placeholder="Share your thoughts on this edition..."
                          class="w-full bg-[#f0f0f0] rounded-xl px-4 py-3.5 text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition resize-none"></textarea>
              </div>

              <button type="submit"
                      class="bg-[#141414] text-white px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-[0.2em] hover:bg-slate-800 transition shadow-md">
                Submit Review
              </button>
              <p class="text-[10px] text-slate-400">Reviews require a completed purchase and are published after moderation.</p>
            </form>
          </div>
        @else
          @auth
            <p class="text-xs text-slate-400 mt-6">You can review this book after completing a purchase and submitting only one review per title.</p>
          @endauth
        @endcan
      @endauth
    </div>

  </div>
</section>
</x-layout.main-layout>