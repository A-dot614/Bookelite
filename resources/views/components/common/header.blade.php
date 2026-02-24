<div class="fixed top-6 inset-x-0 z-[100] px-6 lg:px-12 pointer-events-none">
  <nav class="max-w-7xl mx-auto bg-white/[0.02] backdrop-blur-2xl border border-white/10 px-8 py-3 flex items-center justify-between rounded-full shadow-[0_20px_50px_rgba(0,0,0,0.1)] pointer-events-auto transition-all duration-700 hover:bg-white/[0.05] hover:border-white/20">
    
    <div class="flex items-center group cursor-pointer">
      <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center mr-3 shadow-lg group-hover:rotate-[360deg] transition-transform duration-1000">
        <span class="text-gold font-serif text-xl">E</span>
      </div>
      <h1 class="text-xl font-medium tracking-[0.15em] text-slate-900 uppercase">
        Elite<span class="font-light text-slate-400 italic lowercase tracking-normal ml-1">Archive</span>
      </h1>
    </div>

    <ul class="hidden lg:flex items-center space-x-2 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
      <li>
        <a href="{{route('home')}}" class="relative px-6 py-2 transition-all duration-500 hover:text-slate-900 group">
          Home
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
      <li>
        <a href="{{route('service')}}" class="relative px-6 py-2 transition-all duration-500 hover:text-slate-900 group">
          Services
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
      <li>
        <a href="{{route('about')}}" class="relative px-6 py-2 transition-all duration-500 hover:text-slate-900 group">
          Philosophy
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
      <li>
        <a href="{{route('contact')}}" class="relative px-6 py-2 transition-all duration-500 hover:text-slate-900 group">
          Contact
          <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gold group-hover:w-4 transition-all duration-500"></span>
        </a>
      </li>
    </ul>

    <div class="flex items-center space-x-6">
      <a href="{{route('login')}}" class="hidden md:block text-[10px] font-black uppercase tracking-[0.25em] text-slate-500 hover:text-slate-900 transition-colors">
        Login
      </a>
      
      <a href="{{route('register')}}" class="relative group overflow-hidden px-8 py-3 bg-slate-900 text-white text-[10px] font-bold uppercase tracking-[0.2em] rounded-full transition-all duration-500">
        <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></span>
        <span class="relative z-10">Register</span>
      </a>
    </div>

  </nav>
</div>