<x-layout.admin-layout>
  <section class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-accent">Merchant workflow</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Seller Applications</h2>
        <p class="mt-2 text-sm text-slate-500">Review, approve, reject, or suspend merchant stores.</p>
      </div>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold">{{ session('status') }}</div>
    @endif
    @if(session('error'))
      <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm font-semibold">{{ session('error') }}</div>
    @endif

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex items-center gap-3">
        <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
          <option value="all">All Statuses</option>
          @foreach(['pending', 'approved', 'rejected', 'suspended'] as $status)
            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
          @endforeach
        </select>
        <button type="submit" class="px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition">Filter</button>
        @if(request('status'))
          <a href="{{ route('admin.sellers.index') }}" class="px-4 py-2.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">Clear</a>
        @endif
      </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Store</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Owner</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Titles</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Status</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($sellers as $seller)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <p class="font-bold text-slate-900">{{ $seller->store_name }}</p>
                  <p class="text-xs text-slate-500 mt-0.5">{{ $seller->created_at->format('M d, Y') }}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm font-bold text-slate-900">{{ $seller->user->name ?? '—' }}</p>
                  <p class="text-xs text-slate-500">{{ $seller->user->email ?? '' }}</p>
                </td>
                <td class="px-6 py-4">
                  <span class="text-sm font-black text-slate-900">{{ $seller->books_count }}</span>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $seller->status_color }}">{{ $seller->status }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <a href="{{ route('admin.sellers.show', $seller->id) }}"
                     class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 transition hover:border-slate-900 hover:bg-slate-900 hover:text-white">
                    Review →
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-16 text-center text-sm font-medium text-slate-500">No sellers found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($sellers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $sellers->links() }}</div>
      @endif
    </div>
  </section>
</x-layout.admin-layout>