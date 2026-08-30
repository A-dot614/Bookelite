<x-layout.admin-layout>
  <section class="space-y-6">
    <a href="{{ route('admin.sellers.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition">← Back to Sellers</a>

    @if (session('status'))
      <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-semibold">{{ session('status') }}</div>
    @endif
    @if(session('error'))
      <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm font-semibold">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm font-semibold">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

      <div class="lg:col-span-8 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div>
              <p class="text-xs font-bold uppercase tracking-widest text-accent">Merchant profile</p>
              <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-950 font-serif">{{ $seller->store_name }}</h2>
              <p class="text-xs text-slate-500 mt-1">Owner: {{ $seller->user->name ?? '—' }} · {{ $seller->user->email ?? '' }}</p>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $seller->status_color }}">{{ $seller->status }}</span>
          </div>

          <div class="space-y-3 text-sm pt-6">
            @if($seller->bio)
              <p class="text-slate-600 italic">"{{ $seller->bio }}"</p>
            @endif
            <div class="grid grid-cols-2 gap-4 pt-2">
              <div><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone</p><p class="font-bold text-slate-900 mt-1">{{ $seller->phone ?? '—' }}</p></div>
              <div><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Location</p><p class="font-bold text-slate-900 mt-1">{{ $seller->address ?? '—' }}</p></div>
              <div><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Joined</p><p class="font-bold text-slate-900 mt-1">{{ $seller->created_at->format('M d, Y') }}</p></div>
              <div><p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Reviewed at</p><p class="font-bold text-slate-900 mt-1">{{ $seller->reviewed_at?->format('M d, Y') ?? 'Not yet' }}</p></div>
            </div>
            @if($seller->rejection_reason)
              <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-xs text-red-800">
                <p class="font-black uppercase tracking-widest text-[10px] mb-1">Rejection reason</p>
                {{ $seller->rejection_reason }}
              </div>
            @endif
          </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Listed Titles ({{ $seller->books->count() }})</h3>
          <div class="divide-y divide-slate-100">
            @forelse($seller->books->take(10) as $book)
              <a href="{{ route('admin.books.show', $book->slug) }}" class="flex items-center gap-3.5 py-3 first:pt-0 last:pb-0 hover:bg-slate-50 rounded-xl px-2 transition">
                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="h-12 w-9 rounded-lg object-cover shadow-sm flex-shrink-0">
                <div class="min-w-0 flex-1">
                  <p class="truncate text-xs font-bold text-slate-900">{{ $book->title }}</p>
                  <p class="text-[10px] text-slate-400">{{ config('ecommerce.currency_symbol') }}{{ number_format($book->price, 2) }} · {{ $book->stock }} in stock · {{ $book->status }}</p>
                </div>
              </a>
            @empty
              <p class="py-6 text-center text-xs text-slate-400">No titles listed yet.</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="lg:col-span-4 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-5">
          <h3 class="text-base font-black text-slate-950">Application Decision</h3>

          <form action="{{ route('admin.sellers.status', $seller->id) }}" method="POST" class="space-y-3">
            @csrf
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Decision</label>
            <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
              <option value="approved" {{ $seller->status === 'approved' ? 'selected' : '' }}>Approve</option>
              <option value="rejected" {{ $seller->status === 'rejected' ? 'selected' : '' }}>Reject</option>
              <option value="suspended" {{ $seller->status === 'suspended' ? 'selected' : '' }}>Suspend</option>
            </select>

            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block">Rejection reason (required when rejecting)</label>
            <textarea name="rejection_reason" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 transition resize-none">{{ $seller->rejection_reason ?? '' }}</textarea>

            <button type="submit" class="w-full rounded-full bg-slate-900 px-5 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-slate-700 shadow-sm">
              Save Decision
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</x-layout.admin-layout>