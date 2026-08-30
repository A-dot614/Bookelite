<x-layout.admin-layout>
  <section class="space-y-6">

    <div class="flex items-center justify-between flex-wrap gap-4">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-accent">Trash</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Deleted Collection</h2>
        <p class="mt-2 text-sm text-slate-500">Soft-deleted titles awaiting recovery. Restoring returns them instantly.</p>
      </div>
      <a href="{{ route('admin.books.index') }}"
         class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.25em] text-white hover:bg-accent transition">
        Back to Archive
      </a>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold">{{ session('status') }}</div>
    @endif

    @error('book')
      <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-700 text-sm font-semibold">{{ $message }}</div>
    @enderror

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[700px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Title</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Author</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Deleted At</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($ecommerces as $item)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <span class="h-8 w-6 rounded flex items-center justify-center bg-slate-100 text-slate-400">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </span>
                    <span class="text-sm font-bold text-slate-800 line-through">{{ $item->title }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-500">{{ $item->author }}</td>
                <td class="px-6 py-4 text-xs text-slate-400">{{ $item->deleted_at?->format('M j, Y g:i A') }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-2">
                    <form method="POST" action="{{ route('admin.books.restore', $item->id) }}">
                      @csrf
                      <button class="rounded-full bg-emerald-50 text-emerald-700 px-4 py-2 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition">
                        Restore
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="py-16 text-center">
                  <p class="text-sm font-bold text-slate-500">Trash is empty.</p>
                  <p class="text-xs text-slate-400 mt-1">Deleted titles will appear here for recovery.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="border-t border-slate-100 px-4 py-3">
        {{ $ecommerces->links() }}
      </div>
    </div>

  </section>
</x-layout.admin-layout>