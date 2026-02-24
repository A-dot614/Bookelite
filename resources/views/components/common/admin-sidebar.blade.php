<div class="relative">
  <aside id="dashboardSidebar" class="hidden md:flex flex-col md:w-72 bg-white border-r border-slate-100 h-screen sticky top-0 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]">
    
    <div class="h-24 flex items-center px-8">
      <h1 class="text-2xl font-black tracking-tighter text-slate-900">
        Elite<span class="text-accent italic font-serif">Studio</span>
      </h1>
    </div>

    <nav class="flex-1 px-6 space-y-1">
      <div class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6 mt-4 px-2">Management</div>
      
      <a href="{{route('dashboard')}}" 
         class="group flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-500 ease-out {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
        <div class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white/10' : 'bg-slate-100 group-hover:bg-white group-hover:shadow-sm' }} transition-all duration-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
        </div>
        <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
      </a>

      <a href="{{route('admin.books.index')}}" 
         class="group flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-500 ease-out {{ request()->routeIs('admin.books.*') ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
        <div class="w-8 h-8 flex items-center justify-center rounded-xl {{ request()->routeIs('admin.books.*') ? 'bg-white/10' : 'bg-slate-100 group-hover:bg-white group-hover:shadow-sm' }} transition-all duration-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <span class="text-xs font-black uppercase tracking-widest">Collection</span>
      </a><x-layout.admin-layout>
  <section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">Customers</h2>

      <div class="flex items-center gap-3">
        <input type="search" placeholder="Search customers..." class="px-4 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-accent focus:outline-none">
        <select class="px-3 py-2 rounded-lg bg-white border border-gray-200">
          <option>All</option>
          <option>Active</option>
          <option>Churned</option>
          <option>VIP</option>
        </select>
        <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white rounded-lg">Add Customer</a>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-left min-w-[720px]">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-sm text-gray-500">Customer</th>
            <th class="px-6 py-3 text-sm text-gray-500">Email</th>
            <th class="px-6 py-3 text-sm text-gray-500">Orders</th>
            <th class="px-6 py-3 text-sm text-gray-500">Lifetime Value</th>
            <th class="px-6 py-3 text-sm text-gray-500">Joined</th>
            <th class="px-6 py-3 text-sm text-gray-500">Status</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          <tr>
            <td class="px-6 py-4 flex items-center gap-3">
              <img src="https://i.pravatar.cc/40?img=12" alt="avatar" class="h-10 w-10 rounded-full object-cover">
              <div>
                <div class="font-medium">Maya Thompson</div>
                <div class="text-xs text-gray-500">New York, USA</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">maya@example.com</td>
            <td class="px-6 py-4 text-sm">12</td>
            <td class="px-6 py-4 font-semibold">$1,240</td>
            <td class="px-6 py-4 text-sm text-gray-500">Mar 12, 2024</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-sm">Active</span>
            </td>
          </tr>

          <tr>
            <td class="px-6 py-4 flex items-center gap-3">
              <img src="https://i.pravatar.cc/40?img=28" alt="avatar" class="h-10 w-10 rounded-full object-cover">
              <div>
                <div class="font-medium">Carlos Rivera</div>
                <div class="text-xs text-gray-500">Austin, USA</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">carlos@example.com</td>
            <td class="px-6 py-4 text-sm">4</td>
            <td class="px-6 py-4 font-semibold">$420</td>
            <td class="px-6 py-4 text-sm text-gray-500">Aug 1, 2024</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-sm">At risk</span>
            </td>
          </tr>

          <tr>
            <td class="px-6 py-4 flex items-center gap-3">
              <img src="https://i.pravatar.cc/40?img=5" alt="avatar" class="h-10 w-10 rounded-full object-cover">
              <div>
                <div class="font-medium">Aisha Khan</div>
                <div class="text-xs text-gray-500">Lahore, Pakistan</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">aisha@example.com</td>
            <td class="px-6 py-4 text-sm">29</td>
            <td class="px-6 py-4 font-semibold">$3,920</td>
            <td class="px-6 py-4 text-sm text-gray-500">Jan 10, 2023</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm">VIP</span>
            </td>
          </tr>

          <tr>
            <td class="px-6 py-4 flex items-center gap-3">
              <img src="https://i.pravatar.cc/40?img=45" alt="avatar" class="h-10 w-10 rounded-full object-cover">
              <div>
                <div class="font-medium">Liam O'Connor</div>
                <div class="text-xs text-gray-500">Dublin, Ireland</div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">liam@example.com</td>
            <td class="px-6 py-4 text-sm">0</td>
            <td class="px-6 py-4 font-semibold">$0</td>
            <td class="px-6 py-4 text-sm text-gray-500">Dec 3, 2025</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-sm">Churned</span>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="px-6 py-4 flex items-center justify-between bg-white">
        <div class="text-sm text-gray-500">Showing 1 to 10 of 2,345 customers</div>
        <nav class="inline-flex items-center gap-2">
          <a href="#" class="px-3 py-1 bg-gray-100 rounded">Prev</a>
          <a href="#" class="px-3 py-1 bg-accent text-white rounded">1</a>
          <a href="#" class="px-3 py-1 bg-gray-100 rounded">2</a>
          <a href="#" class="px-3 py-1 bg-gray-100 rounded">Next</a>
        </nav>
      </div>
    </div>

  </section>
</x-layout.admin-layout>    

      <a href="#" class="group flex items-center gap-4 px-4 py-3 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all duration-500 ease-out">
        <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-100 group-hover:bg-white group-hover:shadow-sm transition-all duration-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <span class="text-xs font-black uppercase tracking-widest">Patrons</span>
      </a>

      <div class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6 mt-10 px-2">Analytics</div>

      <a href="#" class="group flex items-center gap-4 px-4 py-3 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all duration-500 ease-out">
        <div class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-100 group-hover:bg-white group-hover:shadow-sm transition-all duration-500">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <span class="text-xs font-black uppercase tracking-widest">Insights</span>
      </a>
    </nav>

    <div class="p-6">
      <div class="bg-slate-50 rounded-[2rem] p-4 flex items-center gap-4 border border-slate-100 group hover:bg-white hover:shadow-xl transition-all duration-500 ease-out">
        <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-white font-black text-xs">AD</div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-black text-slate-900 truncate uppercase tracking-tighter">Admin Studio</p>
          <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Curator Level</p>
        </div>
      </div>
    </div>
  </aside>

  <div class="md:hidden fixed bottom-6 right-6 z-50">
    <button class="w-14 h-14 bg-slate-900 text-white rounded-full shadow-2xl flex items-center justify-center active:scale-90 transition-transform">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
      </svg>
    </button>
  </div>
</div>