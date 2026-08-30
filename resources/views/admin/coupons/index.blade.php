<x-layout.admin-layout>
  <section class="space-y-8">

    <div class="flex items-start justify-between gap-6 flex-wrap">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-accent">Promotions</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Promo Codes</h2>
        <p class="mt-2 text-sm text-slate-500">Create discount campaigns redeemable at checkout.</p>
      </div>
      <a href="{{ route('admin.coupons.create') }}"
         class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2.5 text-[10px] font-black uppercase tracking-[0.25em] text-white hover:bg-accent transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Code
      </a>
    </div>

    @if(session('status'))
      <div class="rounded-full bg-emerald-50 border border-emerald-200 px-6 py-3 text-xs font-bold text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Code</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Discount</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Minimum</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Usage</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Window</th>
              <th class="py-3 px-4 font-black uppercase tracking-widest text-slate-500">Status</th>
              <th class="py-3 px-4 text-right font-black uppercase tracking-widest text-slate-500">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($coupons as $coupon)
              <tr class="hover:bg-slate-50 transition">
                <td class="py-3.5 px-4">
                  <span class="font-black text-slate-900 tracking-wider">{{ $coupon->code }}</span>
                  <span class="block text-[10px] text-slate-400 mt-0.5">{{ $coupon->type }} discount</span>
                </td>
                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $coupon->typeLabel() }}</td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ $coupon->min_order_amount !== null ? config('ecommerce.currency_symbol').number_format($coupon->min_order_amount, 2) : 'None' }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  @if($coupon->starts_at || $coupon->expires_at)
                    {{ $coupon->starts_at?->format('M j') ?? 'Now' }} – {{ $coupon->expires_at?->format('M j, Y') ?? 'Forever' }}
                  @else
                    Always
                  @endif
                </td>
                <td class="py-3.5 px-4">
                  $isExpired = $coupon->expires_at && now()->gt($coupon->expires_at);
                @endphp
                <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest {{ $coupon->is_active && !$isExpired ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                  {{ $coupon->is_active ? ($isExpired ? 'Expired' : 'Active') : 'Paused' }}
                </span>
                </td>
                <td class="py-3.5 px-4">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}"
                       class="rounded-full bg-slate-900 text-white px-3 py-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-accent transition">Edit</a>
                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this promo code?');">
                      @csrf @method('DELETE')
                      <button class="rounded-full bg-red-50 text-red-600 px-3 py-1.5 text-[9px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-16 text-center text-slate-400">
                  <p class="text-sm font-bold text-slate-500">No promo codes yet.</p>
                  <p class="text-xs mt-1">Create your first code to start rewarding patrons.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="border-t border-slate-100 px-4 py-3">
        {{ $coupons->links() }}
      </div>
    </div>

  </section>
</x-layout.admin-layout>