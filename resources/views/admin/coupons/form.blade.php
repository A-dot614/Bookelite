<x-layout.admin-layout>
  <section class="max-w-3xl space-y-8">

    <div>
      <p class="text-xs font-bold uppercase tracking-widest text-accent">Promotions</p>
      <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">{{ $coupon ? 'Edit Promo Code' : 'New Promo Code' }}</h2>
    </div>

    @if ($errors->any())
      <div class="rounded-2xl bg-red-50 border border-red-200 p-5 text-xs text-red-700">
        <p class="font-bold mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside space-y-0.5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST"
          action="{{ $coupon ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
          class="space-y-6 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
      @csrf
      @if($coupon) @method('PUT') @endif

      <div class="grid sm:grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Code</label>
          <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" placeholder="SUMMER25"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Discount Type</label>
          <select name="type"
                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
            <option value="percentage" @selected(old('type', $coupon->type ?? 'percentage') === 'percentage')>Percentage (%)</option>
            <option value="fixed" @selected(old('type', $coupon->type ?? '') === 'fixed')>Fixed amount</option>
          </select>
        </div>
      </div>

      <div class="grid sm:grid-cols-3 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Value</label>
          <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value ?? '') }}" placeholder="10 or 10.00"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Min Order Amount</label>
          <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Max Discount Cap</label>
          <input type="number" step="0.01" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
      </div>

      <div class="grid sm:grid-cols-3 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Usage Limit (total)</label>
          <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" placeholder="Unlimited"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Starts At</label>
          <input type="date" name="starts_at" value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d')) }}"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Expires At</label>
          <input type="date" name="expires_at" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d')) }}"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
      </div>

      <label class="flex items-center gap-3 cursor-pointer">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))
               class="rounded border-slate-300 text-amber-500 focus:ring-amber-400">
        <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Coupon is active</span>
      </label>

      <div class="flex gap-3 pt-2">
        <button type="submit"
                class="rounded-full bg-slate-900 px-8 py-3 text-[10px] font-black uppercase tracking-[0.25em] text-white hover:bg-accent transition">
          {{ $coupon ? 'Save Changes' : 'Create Code' }}
        </button>
        <a href="{{ route('admin.coupons.index') }}"
           class="rounded-full border border-slate-200 px-8 py-3 text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 hover:text-slate-900 transition">
          Cancel
        </a>
      </div>
    </form>

  </section>
</x-layout.admin-layout>