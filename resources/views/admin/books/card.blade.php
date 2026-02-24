<x-layout.admin-layout>
  <section class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
      <div class="space-y-2">
        <span class="text-[10px] font-black uppercase tracking-[0.4em] text-accent">Inventory Control</span>
        <h2 class="text-4xl md:text-5xl font-serif italic text-slate-900 leading-none">
          The <span class="font-sans not-italic font-black text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">Collection.</span>
        </h2>
      </div>

      <a href="{{ route('admin.books.create') }}" 
         class="inline-flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-accent hover:shadow-2xl hover:shadow-accent/20 hover:-translate-y-1 transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        <span>Curate New Title</span>
      </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-sm overflow-hidden transition-all duration-700 hover:shadow-2xl hover:shadow-slate-200/50">
      <table class="w-full text-left min-w-[800px] border-collapse">
        <thead>
          <tr class="border-b border-slate-50">
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Masterpiece Details</th>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Value</th>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Recognition</th>
            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Orchestration</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-50">
          @forelse($ecommerces as $item)
            <tr class="group hover:bg-slate-50/50 transition-colors duration-500">
              <td class="px-8 py-6">
                <div class="flex items-center gap-6">
                  <div class="relative w-16 h-20 flex-shrink-0 overflow-hidden rounded-xl shadow-lg group-hover:scale-110 group-hover:rotate-2 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]">
                    <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=200' }}" 
                         alt="{{ $item->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                  </div>
                  <div class="space-y-1">
                    <div class="text-sm font-black text-slate-900 tracking-tight">{{ $item->title }}</div>
                    <div class="text-[10px] font-medium text-slate-400 max-w-xs leading-relaxed uppercase tracking-wider">
                      {{ \Illuminate\Support\Str::limit($item->description, 60) }}
                    </div>
                  </div>
                </div>
              </td>

              <td class="px-8 py-6">
                <span class="text-sm font-serif italic font-bold text-slate-900">
                  ${{ number_format($item->price, 2) }}
                </span>
              </td>

              <td class="px-8 py-6">
                <div class="flex items-center gap-1">
                  <span class="text-xs font-black text-slate-900">{{ $item->rating ?? '0.0' }}</span>
                  <span class="text-accent text-xs">★</span>
                </div>
              </td>

              <td class="px-8 py-6 text-right">
                <div class="flex items-center justify-end gap-3">
                  <a href="{{ route('admin.books.show', $item->slug) }}" 
                     class="p-3 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all duration-500" 
                     title="View Details">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </a>

                  <button type="button" 
                          class="px-4 py-2 rounded-xl border border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:border-accent hover:text-accent transition-all duration-500">
                    Edit
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-8 py-20 text-center">
                <p class="text-sm font-serif italic text-slate-400">The collection is currently empty.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
</x-layout.admin-layout>