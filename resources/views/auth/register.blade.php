<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EliteBooks | Begin Your Legacy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes subtleDrift {
            0% { transform: scale(1.1) translateX(0); }
            100% { transform: scale(1.2) translateX(20px); }
        }
        .animate-drift { animation: subtleDrift 20s infinite alternate linear; }
        
        /* Custom Gold Accent if not defined in tailwind config */
        .text-gold { color: #c5a059; }
        .bg-gold { background-color: #c5a059; }
        .border-gold { border-color: #c5a059; }
    </style>
</head>
<body class="bg-[#fafafa] min-h-screen flex items-center justify-center p-4 lg:p-12 overflow-x-hidden selection:bg-slate-900 selection:text-white">

    <div class="absolute top-10 left-10 text-[10px] font-black uppercase tracking-[1em] text-slate-200 rotate-90 origin-left select-none">
        Membership Protocol — v.2026
    </div>

    <div class="w-full max-w-7xl grid lg:grid-cols-12 bg-white shadow-[0_100px_150px_-50px_rgba(0,0,0,0.12)] border border-slate-100 overflow-hidden relative">
        
        <div class="lg:col-span-5 p-10 md:p-20 flex flex-col justify-center bg-white z-10">
            <div class="mb-16">
                <div class="w-12 h-[1px] bg-gold mb-8"></div>
                <h2 class="text-4xl font-serif text-slate-900 tracking-tighter mb-2">Request Invitation</h2>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Section Alpha: Personal Dossier</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-10">
                @csrf

                <div class="relative group">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder=" "
                           class="peer w-full bg-transparent py-3 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-all">
                    <label for="name" class="absolute left-0 top-0 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-xs peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[9px] peer-focus:tracking-[0.3em] peer-focus:text-gold">
                        Legal Identity
                    </label>
                </div>

                <div class="relative group">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder=" "
                           class="peer w-full bg-transparent py-3 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-all">
                    <label for="email" class="absolute left-0 top-0 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-xs peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[9px] peer-focus:tracking-[0.3em] peer-focus:text-gold">
                        Digital Mail
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-10">
                    <div class="relative group">
                        <input id="password" type="password" name="password" required placeholder=" "
                               class="peer w-full bg-transparent py-3 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-all">
                        <label for="password" class="absolute left-0 top-0 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-xs peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[9px] peer-focus:tracking-[0.3em] peer-focus:text-gold">
                            Passphrase
                        </label>
                    </div>

                    <div class="relative group">
                        <input id="password_confirmation" type="password" name="password_confirmation" required placeholder=" "
                               class="peer w-full bg-transparent py-3 text-slate-900 font-serif text-lg border-b border-slate-100 focus:outline-none focus:border-gold transition-all">
                        <label for="password_confirmation" class="absolute left-0 top-0 text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-xs peer-placeholder-shown:tracking-normal peer-focus:top-0 peer-focus:text-[9px] peer-focus:tracking-[0.3em] peer-focus:text-gold">
                            Verify
                        </label>
                    </div>
                </div>

                <div class="pt-8 flex flex-col items-start space-y-8">
                    <button type="submit"
                            class="relative group px-12 py-5 bg-slate-900 text-white overflow-hidden transition-all duration-500 hover:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)]">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.5em]">Initiate Membership</span>
                        <div class="absolute inset-0 bg-gold translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    </button>

                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-300">
                        Part of the guild? <a href="{{ route('login') }}" class="text-slate-900 border-b border-slate-900 pb-1 ml-2 hover:text-gold hover:border-gold transition-all">Authorize Entry</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="hidden lg:block lg:col-span-7 relative overflow-hidden bg-slate-900">
            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=2070&auto=format&fit=crop" 
                 class="absolute inset-0 w-full h-full object-cover opacity-50 animate-drift grayscale hover:grayscale-0 transition-all duration-1000" 
                 alt="Library Atmosphere">
            
            <div class="absolute inset-0 bg-gradient-to-r from-white via-transparent to-transparent z-10"></div>
            
            <div class="absolute top-20 right-20 text-right z-20">
                <span class="text-gold text-[10px] font-black uppercase tracking-[0.6em] block mb-4">Volume 01</span>
                <h1 class="text-7xl font-serif text-white leading-[0.85] tracking-tighter">
                    Write <br> Your <br> <span class="italic text-slate-400">Legacy.</span>
                </h1>
            </div>

            <div class="absolute bottom-20 right-20 flex items-center space-x-4 z-20">
                <span class="text-[9px] font-bold text-white/40 uppercase tracking-widest">Global Studios: Karachi / London / Paris</span>
            </div>
        </div>
    </div>

</body>
</html>