<x-layout.admin-layout>
  <section class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-widest text-accent">Patron directory</p>
        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950 sm:text-3xl">Customers</h2>
        <p class="mt-2 text-sm text-slate-500">Monitor registered readers, acquisition volume, and customer lifetime value.</p>
      </div>
    </div>

    <!-- Search bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
      <form method="GET" action="{{ route('admin.customers.index') }}" class="flex-1 flex items-center gap-3">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Search customer by name or email..."
                 class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-full text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white transition">
        </div>
        <button type="submit" class="px-6 py-2.5 rounded-full bg-slate-900 text-white text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition">
          Search
        </button>
        @if(request('q'))
          <a href="{{ route('admin.customers.index') }}" class="px-4 py-2.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
            Clear
          </a>
        @endif
      </form>
    </div>

    <!-- Customers Table -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full min-w-[700px] text-left">
          <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Customer</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Registered</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Orders Placed</th>
              <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-500">Total Spent</th>
              <th class="px-6 py-4 text-right text-xs font-black uppercase tracking-widest text-slate-500">Role</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100">
            @forelse($customers as $customer)
              <tr class="transition hover:bg-slate-50/70">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-900 text-white font-bold text-xs flex items-center justify-center uppercase shadow-xs">
                      {{ substr($customer->name, 0, 2) }}
                    </div>
                    <div>
                      <p class="font-bold text-slate-900 text-sm">{{ $customer->name }}</p>
                      <p class="text-xs text-slate-400">{{ $customer->email }}</p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4 text-xs text-slate-600">
                  {{ $customer->created_at->format('M d, Y') }}
                </td>

                <td class="px-6 py-4">
                  <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                    {{ $customer->orders_count ?? 0 }} orders
                  </span>
                </td>

                <td class="px-6 py-4">
                  <span class="text-sm font-black text-slate-900 font-serif">
                    ${{ number_format($customer->orders_sum_total ?? 0, 2) }}
                  </span>
                </td>

                <td class="px-6 py-4 text-right">
                  <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">
                    Active Patron
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-16 text-center text-slate-500 text-sm">
                  No customers found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
          {{ $customers->links() }}
        </div>
      @endif
    </div>
  </section>
</x-layout.admin-layout>
