<x-layout.seller-layout>
  <section class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Merchant Inventory</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">My Book Catalog</h2>
        <p class="mt-2 text-sm text-slate-500">Manage your bookstore's listed items, stock levels, and acquisition pricing.</p>
      </div>

      <a href="{{ route('seller.books.create') }}"
         class="inline-flex items-center justify-center gap-2 rounded-full bg-[#141414] px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-800 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gold" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        List New Masterpiece
      </a>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('status') }}
      </div>
    @endif

    <!-- Search bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
      <form method="GET" action="{{ route('seller.books.index') }}" class="flex-1 flex items-center gap-3">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search inventory by title, author, or ISBN..."
                 class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition">
        </div>
        <button type="submit" class="px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition">
          Filter
        </button>
        @if(request('q'))
          <a href="{{ route('seller.books.index') }}" class="px-4 py-2.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
            Clear
          </a>
        @endif
      </form>
    </div>

    <!-- Books Table -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Book Details</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Category</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Stock</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Price</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse($books as $item)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-4">
                    <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=180' }}"
                         alt="{{ $item->title }}"
                         class="h-14 w-11 flex-shrink-0 rounded-lg object-cover shadow-sm">
                    <div class="min-w-0">
                      <p class="font-bold text-slate-900 text-sm truncate max-w-xs">{{ $item->title }}</p>
                      <p class="text-xs text-slate-500">by {{ $item->author ?? 'Unknown' }}</p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                    {{ $item->category ?? 'General' }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  @if($item->stock > 0)
                    <span class="text-xs font-bold text-emerald-700">{{ $item->stock }} in stock</span>
                  @else
                    <span class="text-xs font-bold text-red-600">Out of stock</span>
                  @endif
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm font-black text-slate-900 font-serif">${{ number_format($item->price, 2) }}</span>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('detail', $item->slug) }}" target="_blank"
                       class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:bg-slate-900 hover:text-white transition"
                       title="View on storefront">
                      <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    <a href="{{ route('seller.books.edit', $item->slug) }}"
                       class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-400 transition"
                       title="Edit listing">
                      <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </a>

                    <form action="{{ route('seller.books.destroy', $item->slug) }}" method="POST" onsubmit="return confirm('Remove this book from your catalog?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition"
                              title="Delete listing">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-16 text-center text-slate-500 text-sm">
                  <p>You have not listed any books yet.</p>
                  <a href="{{ route('seller.books.create') }}" class="mt-4 inline-flex rounded-full bg-slate-900 px-6 py-2.5 text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-800">Add First Book</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($books->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
          {{ $books->links() }}
        </div>
      @endif
    </div>
  </section>
</x-layout.seller-layout>
