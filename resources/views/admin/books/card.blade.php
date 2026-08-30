<x-layout.admin-layout>
  <section class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-accent">Inventory control</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Collection Archive</h2>
        <p class="mt-2 text-sm text-slate-500">Manage titles, stock, pricing, and curation records.</p>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('admin.books.trash') }}"
           class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 px-5 py-3 text-xs font-black uppercase tracking-widest text-slate-500 transition hover:border-slate-900 hover:text-slate-900">
          Trash
        </a>
        <a href="{{ route('admin.books.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-accent">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
          </svg>
          Add Masterpiece
        </a>
      </div>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('status') }}
      </div>
    @endif

    <!-- Search bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
      <form method="GET" action="{{ route('admin.books.index') }}" class="flex-1 flex items-center gap-3">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title, author, category, or ISBN..."
                 class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition">
        </div>
        <button type="submit" class="px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition">
          Filter
        </button>
        @if(request('q'))
          <a href="{{ route('admin.books.index') }}" class="px-4 py-2.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
            Clear
          </a>
        @endif
      </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Book Details</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Category</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Stock</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Price</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Rating</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse($ecommerces as $item)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-4">
                    <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=180' }}"
                         alt="{{ $item->title }}"
                         class="h-16 w-12 flex-shrink-0 rounded-lg object-cover shadow-sm">
                    <div class="min-w-0">
                      <a href="{{ route('admin.books.show', $item->slug) }}" class="font-bold text-slate-900 hover:text-accent transition truncate block max-w-xs">
                        {{ $item->title }}
                      </a>
                      <p class="text-xs text-slate-500 mt-0.5">by {{ $item->author ?? 'Unknown' }}</p>
                      @if($item->isbn)
                        <p class="text-[10px] text-slate-400 font-mono">ISBN: {{ $item->isbn }}</p>
                      @endif
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
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                      {{ $item->stock }} in stock
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600">
                      <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                      Out of stock
                    </span>
                  @endif
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm font-black text-slate-900">${{ number_format($item->price, 2) }}</span>
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.95-.69l1.07-3.292z" />
                    </svg>
                    {{ number_format($item->rating ?? 0, 1) }}
                  </span>
                </td>

                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.books.show', $item->slug) }}"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-slate-900 hover:bg-slate-900 hover:text-white"
                       title="View details">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </a>

                    <a href="{{ route('admin.books.edit', $item->slug) }}"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-accent hover:bg-amber-50 hover:text-amber-700"
                       title="Edit book">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                      </svg>
                    </a>

                    <form action="{{ route('admin.books.destroy', $item->slug) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-red-500 hover:bg-red-50 hover:text-red-600"
                              title="Delete book">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                  <p class="text-sm font-medium text-slate-500">No books found matching your criteria.</p>
                  <a href="{{ route('admin.books.create') }}" class="mt-4 inline-flex rounded-full bg-slate-900 px-6 py-2.5 text-xs font-black uppercase tracking-widest text-white transition hover:bg-accent">Add Masterpiece</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($ecommerces->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
          {{ $ecommerces->links() }}
        </div>
      @endif
    </div>
  </section>
</x-layout.admin-layout>
