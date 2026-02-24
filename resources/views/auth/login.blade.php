<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EliteBooks | Personnel Entry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(1000%); }
        }
        .animate-scan { animation: scanline 8s linear infinite; opacity: 0.05; }
        
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px white inset;
            -webkit-text-fill-color: #0f172a;
        }
    </style>
</head>
<body class="bg-[#fafafa] min-h-screen flex items-center justify-center p-4 lg:p-8 overflow-x-hidden selection:bg-slate-900 selection:text-white">

    <div class="absolute top-0 left-0 w-full h-1 bg-slate-900/10">
        <div class="h-full bg-slate-900 w-full opacity-30"></div>
    </div>
    
    <div class="absolute top-20 right-20 text-[15vw] font-black text-slate-900/[0.02] select-none pointer-events-none tracking-tighter">SECURE</div>

    <div class="w-full max-w-5xl grid lg:grid-cols-12 bg-white shadow-[0_80px_120px_-40px_rgba(0,0,0,0.08)] border border-slate-100 relative">
        
        <div class="lg:col-span-5 p-12 md:p-20 bg-slate-900 flex flex-col justify-between relative overflow-hidden border-r border-slate-900">
            <div class="absolute inset-0 bg-gradient-to-b from-white to-transparent h-20 animate-scan pointer-events-none"></div>

            <div class="space-y-12 relative z-10">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-[10px] text-slate-900 font-bold italic">EB</div>
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400">Vault 04 Access</span>
                </div>
                
                <div class="space-y-6">
                    <h1 class="text-5xl font-serif text-white leading-tight">Return to the <br/> <span class="italic text-slate-500">Sanctuary.</span></h1>
                    <p class="text-sm text-slate-400 leading-loose font-light max-w-xs">
                        Provide your digital signature and security phrase to access your archived acquisitions and private library holdings.
                    </p>
                </div>
            </div>

            <div class="space-y-4 pt-12 relative z-10">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <span class="text-[9px] font-black uppercase tracking-widest text-white">Security Status</span>
                    <span class="flex items-center gap-2 text-[9px] font-black uppercase tracking-widest text-emerald-400">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> Authorized
                    </span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 p-12 md:p-20 bg-white flex flex-col justify-center">
            <div class="mb-16 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-serif text-slate-900 tracking-tight">Authorize Entry</h2>
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1">Section Beta — Verification</p>
                </div>
                <span class="text-[10px] font-bold text-slate-200 tracking-tighter">REF: EB-2026</span>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-12">
                @csrf

                <div class="relative group">
                    <input id="email" type="email" name="email" required placeholder="Digital Mail"
                           class="peer w-full bg-transparent py-3 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-slate-900 transition-all placeholder-transparent">
                    <label for="email" class="absolute left-0 top-0 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-xs peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[9px] peer-focus:tracking-[0.3em] peer-focus:text-slate-900">
                        Personnel Identity
                    </label>
                </div>

                <div class="relative group">
                    <div class="flex justify-between items-center absolute right-0 top-0 z-10">
                         <a href="{{ route('password.request') }}" class="text-[9px] font-black uppercase tracking-widest text-slate-200 hover:text-slate-900 transition-colors">Lost Phrase?</a>
                    </div>
                    <input id="password" type="password" name="password" required placeholder="Security Phrase"
                           class="peer w-full bg-transparent py-3 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-slate-900 transition-all placeholder-transparent">
                    <label for="password" class="absolute left-0 top-0 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-xs peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[9px] peer-focus:tracking-[0.3em] peer-focus:text-slate-900">
                        Security Phrase
                    </label>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="sr-only peer">
                        <div class="w-8 h-4 bg-slate-100 rounded-full peer peer-checked:bg-slate-900 transition-all relative">
                            <div class="absolute top-1 left-1 w-2 h-2 bg-white rounded-full transition-transform peer-checked:translate-x-4"></div>
                        </div>
                        <span class="ml-3 text-[9px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 transition-colors">Maintain Connection</span>
                    </label>
                </div>

                <div class="pt-6">
                    <button type="submit" 
                            class="relative w-full py-6 bg-slate-900 text-white overflow-hidden group transition-all duration-500 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] hover:-translate-y-1">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.5em]">Authorize Access</span>
                        <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    </button>
                </div>
            </form>

            <div class="mt-20 text-center lg:text-left border-t border-slate-50 pt-8">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-300">
                    New to the Collection? 
                    <a href="{{ route('register') }}" class="text-slate-900 border-b border-slate-900 pb-1 ml-2 hover:text-slate-400 hover:border-slate-400 transition-all">Request Invitation</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>