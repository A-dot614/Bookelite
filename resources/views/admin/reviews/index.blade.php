<x-layout.admin-layout>
  <section class="space-y-6">
    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-accent">Moderation queue</p>
      <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Book Reviews</h2>
      <p class="mt-2 text-sm text-slate-500">Approve or hide patron reviews. Approvals recalculate a title's rating.</p>
    </div>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold">{{ session('status') }}</div>
    @endif

    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex items-center gap-3">
        <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
          <option value="all">All Reviews</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Awaiting Approval</option>
          <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
        </select>
        <button type="submit" class="px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition">Filter</button>
      </form>
    </div>

    <div class="space-y-4">
      @forelse($reviews as $review)
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
          <div class="flex items-center justify-between gap-4 mb-3">
            <div class="flex items-center gap-3 min-w-0">
              <span class="w-9 h-9 rounded-full bg-slate-900 text-white text-xs font-black flex items-center justify-center uppercase flex-shrink-0">{{ substr($review->user->name ?? 'A', 0, 1) }}</span>
              <div class="min-w-0">
                <p class="text-sm font-bold text-slate-900 truncate">{{ $review->user->name ?? 'Patron' }}</p>
                <p class="text-[10px] text-slate-400">{{ $review->created_at->format('M d, Y') }} · on <span class="font-bold text-slate-600">{{ $review->book->title ?? 'Deleted book' }}</span></p>
              </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
              <div class="flex gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                  <span class="text-sm {{ $i <= $review->rating ? 'text-amber-500' : 'text-slate-200' }}">★</span>
                @endfor
              </div>
              <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $review->is_approved ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                {{ $review->is_approved ? 'Approved' : 'Pending' }}
              </span>
            </div>
          </div>
          <p class="text-sm text-slate-600 italic leading-relaxed">"{{ $review->body }}"</p>

          <div class="mt-4 border-t border-slate-100 pt-4">
            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
              @csrf
              <button type="submit"
                      class="inline-flex rounded-full px-5 py-2.5 text-xs font-black uppercase tracking-widest transition
                      {{ $review->is_approved ? 'border border-slate-200 text-slate-600 hover:border-red-500 hover:text-red-600' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                {{ $review->is_approved ? 'Hide Review' : 'Approve & Publish' }}
              </button>
            </form>
          </div>
        </div>
      @empty
        <p class="py-16 text-center text-sm font-medium text-slate-500">No reviews match your filter.</p>
      @endforelse
    </div>

    @if($reviews->hasPages())
      <div>{{ $reviews->links() }}</div>
    @endif
  </section>
</x-layout.admin-layout>