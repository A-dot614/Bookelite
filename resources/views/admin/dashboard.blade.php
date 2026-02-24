<x-layout.admin-layout>
    <div class="space-y-10 p-2">
        <section class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-10 md:p-16 text-white shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="space-y-4 max-w-xl text-center md:text-left">
                    <span class="text-[10px] font-black uppercase tracking-[0.5em] text-accent">System Status: Optimal</span>
                    <h2 class="text-4xl md:text-6xl font-serif italic leading-tight">
                        Welcome back, <span class="font-sans not-italic font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">Curator.</span>
                    </h2>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed tracking-wide">
                        Your collection is thriving. Today's metrics show a 12% increase in global engagement. Ready to manage the legacy?
                    </p>
                </div>
                <div class="relative group">
                    <div class="absolute inset-0 bg-accent blur-2xl opacity-20 group-hover:opacity-40 transition-opacity duration-700"></div>
                    <div class="w-32 h-32 bg-white/5 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center text-5xl shadow-inner group-hover:scale-110 transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]">
                        📚
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @php 
                $stats = [
                    ['label' => 'Total Collection', 'value' => '3,245', 'trend' => '+24'],
                    ['label' => 'Daily Acquisitions', 'value' => '124', 'trend' => '+5'],
                    ['label' => 'Gross Revenue', 'value' => '$18,920', 'trend' => 'Stable'],
                    ['label' => 'Global Patrons', 'value' => '1,042', 'trend' => '+18']
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="group bg-white border border-slate-100 p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">{{ $stat['label'] }}</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter">{{ $stat['value'] }}</h3>
                    <span class="text-[9px] font-black px-2 py-1 bg-slate-50 text-accent rounded-lg group-hover:bg-accent group-hover:text-white transition-colors duration-500">
                        {{ $stat['trend'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-7 bg-white border border-slate-100 p-10 rounded-[3rem] shadow-sm">
                <div class="flex justify-between items-center mb-12">
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900">Revenue Velocity</h3>
                    <span class="text-[10px] font-bold text-slate-400">7-Day Window</span>
                </div>
                
                <div class="flex items-end justify-between h-48 gap-4 px-2">
                    @foreach([30, 55, 85, 65, 45, 90, 100] as $height)
                    <div class="flex-1 group relative flex flex-col items-center">
                        <div class="w-full bg-slate-50 rounded-2xl relative overflow-hidden h-full flex items-end">
                            <div class="w-full bg-slate-900 group-hover:bg-accent transition-all duration-1000 ease-[cubic-bezier(0.22,1,0.36,1)] rounded-t-xl" style="height: {{ round($height * 1.92) }}px;"></div>
                        </div>
                        <span class="mt-4 text-[9px] font-black text-slate-300 uppercase tracking-widest group-hover:text-slate-900 transition-colors">
                            {{ ['M','T','W','T','F','S','S'][$loop->index] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-5 bg-slate-900 text-white p-10 rounded-[3rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10"></div>
                
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-accent mb-10">Recent Ledger</h3>
                
                <div class="space-y-6">
                    @foreach(['1024' => '120', '1023' => '80', '1022' => '150', '1021' => '200'] as $id => $amt)
                    <div class="group flex items-center justify-between py-4 border-b border-white/5 hover:border-accent/30 transition-colors duration-500">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-[10px] font-black group-hover:bg-accent transition-colors">
                                #{{ $id }}
                            </div>
                            <span class="text-xs font-bold text-slate-400 group-hover:text-white transition-colors tracking-wide">Standard Acquisition</span>
                        </div>
                        <span class="text-sm font-black italic font-serif text-accent">${{ $amt }}</span>
                    </div>
                    @endforeach
                </div>

                <button class="w-full mt-10 py-4 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white hover:text-slate-900 transition-all duration-500">
                    View Full Ledger
                </button>
            </div>
        </div>
    </div>
</x-layout.admin-layout>