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
        </svg><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead CRM Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-slate-800">
                Lead<span class="text-blue-400">Flow</span>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                <a href="#" class="flex items-center space-x-3 p-3 bg-blue-600 rounded-lg text-white">
                    <i class="fas fa-th-large w-5"></i> <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 hover:bg-slate-800 rounded-lg transition text-gray-400 hover:text-white">
                    <i class="fas fa-users w-5"></i> <span>Leads</span>
                </a>
                <a href="#" class="flex items-center space-x-3 p-3 hover:bg-slate-800 rounded-lg transition text-gray-400 hover:text-white">
                    <i class="fas fa-chart-line w-5"></i> <span>Reporting</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form action="/logout" method="POST"> <button type="submit" class="w-full flex items-center space-x-3 p-3 text-gray-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition group">
                        <i class="fas fa-sign-out-alt w-5 group-hover:text-red-400"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
                <h1 class="text-xl font-semibold text-gray-800">CRM Overview</h1>
                <div class="flex items-center space-x-6">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                        + New Lead
                    </button>
                    
                    <div class="flex items-center space-x-3 border-l pl-6">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800 leading-none">Admin User</p>
                            <span class="text-xs text-gray-500">Super Admin</span>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            A
                        </div>
                        <button title="Logout" class="text-gray-400 hover:text-red-600 transition ml-2">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-sm font-medium text-gray-500 uppercase">New Leads</p>
                        <h3 class="text-2xl font-bold text-gray-900">42</h3>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <p class="text-sm font-medium text-gray-500 uppercase">Contacted</p>
                        <h3 class="text-2xl font-bold text-gray-900">18</h3>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-orange-400">
                        <p class="text-sm font-medium text-gray-500 uppercase">Warm</p>
                        <h3 class="text-2xl font-bold text-gray-900">7</h3>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-green-500">
                        <p class="text-sm font-medium text-gray-500 uppercase">Closed</p>
                        <h3 class="text-2xl font-bold text-gray-900">12</h3>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800">Recent Leads</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Lead Name</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Source</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">Johnathan Miller</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Warm</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">LinkedIn</td>
                                    <td class="px-6 py-4 text-right italic text-gray-400 text-sm">
                                        Click to edit
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
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