<x-layout.main-layout>
<section class="min-h-screen bg-[#fafafa] pt-32 pb-24 px-6">
  <div class="max-w-6xl mx-auto space-y-10">
    
    <div class="space-y-2">
      <div class="flex items-center space-x-3">
        <span class="w-8 h-[1px] bg-gold"></span>
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-gold">Personal Library</span>
      </div>
      <h1 class="text-4xl md:text-5xl font-serif text-slate-900 font-bold tracking-tight">
        Saved <span class="italic font-normal text-slate-400">Wishlist.</span>
      </h1>
      <p class="text-xs text-slate-500 font-light">
        Masterpieces bookmarked for future acquisition into your personal collection.
      </p>
    </div>

    @if (session('status'))
      <div class="rounded-full bg-slate-900 text-white px-6 py-3.5 text-xs font-bold uppercase tracking-widest flex items-center gap-3 shadow-lg">
        <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
        {{ session('status') }}
      </div>
    @endif

    @if($wishlists->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($wishlists as $wishlist)
          @php $item = $wishlist->book; @endphp
          @if($item)
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
              <div>
                <div class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-slate-100 mb-5">
                  <a href="{{ route('detail', $item->slug) }}">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                  </a>

                  <!-- Remove from Wishlist Form -->
                  <form action="{{ route('wishlist.toggle', $item->slug) }}" method="POST" class="absolute top-3 right-3">
                    @csrf
                    <button type="submit" 
                            class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm"
                            title="Remove from wishlist">
                      <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>
                  </form>
                </div>

                <div class="space-y-1.5">
                  <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ $item->category ?? 'General' }}</span>
                  <h3 class="text-base font-serif font-bold text-slate-900 truncate">
                    <a href="{{ route('detail', $item->slug) }}" class="hover:text-gold transition">{{ $item->title }}</a>
                  </h3>
                  <p class="text-xs text-slate-400">by {{ $item->author }}</p>
                </div>
              </div>

              <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-lg font-serif font-bold text-slate-900">${{ number_format($item->price, 2) }}</span>

                @if($item->stock > 0)
                  <form action="{{ route('cart.add', $item->slug) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" 
                            class="bg-[#141414] text-white px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-wider hover:bg-slate-800 transition">
                      Move to Bag
                    </button>
                  </form>
                @else
                  <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Sold Out</span>
                @endif
              </div>
            </div>
          @endif
        @endforeach
      </div>

      @if($wishlists->hasPages())
        <div class="pt-6 flex justify-center">
          {{ $wishlists->links() }}
        </div>
      @endif
    @else
      <div class="bg-white rounded-3xl border border-slate-100 p-16 text-center max-w-xl mx-auto shadow-sm space-y-6">
        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <div>
          <h3 class="text-2xl font-serif font-bold text-slate-900">No saved works yet.</h3>
          <p class="text-xs text-slate-500 font-light mt-2 max-w-sm mx-auto">
            Click the heart icon on any book across our archive to curate your personal wishlist.
          </p>
        </div>
        <a href="{{ route('home') }}" 
           class="inline-block bg-[#141414] text-white px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition shadow-md">
          Browse Archive
        </a>
      </div>
    @endif

  </div>
</section>
</x-layout.main-layout>
