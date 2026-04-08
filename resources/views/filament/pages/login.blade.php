<div class="w-full relative group">

  <form wire:submit="authenticate" class="relative z-10 space-y-7 w-full">

    <div class="flex flex-col items-center text-center pb-2">

      <div class="relative w-20 h-20 mb-5">
        <div
          class="absolute inset-0 bg-gradient-to-tr from-rose-500/40 to-red-900/40 dark:from-rose-500/30 dark:to-red-900/30 rounded-full blur-xl animate-pulse">
        </div>
        <img src="{{ asset('img/rsumpyk.png') }}"
          class="relative w-full h-full object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-110 hover:-translate-y-1">
      </div>

      <h1
        class="font-extrabold text-3xl md:text-4xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-rose-800 to-gray-900 dark:from-white dark:via-rose-400 dark:to-white pb-1">
        SIMARUT
      </h1>

      <div class="h-1 w-12 bg-gradient-to-r from-rose-600 to-red-900 rounded-full mt-3 mb-4 opacity-80"></div>

      <div class="leading-relaxed">
        <p class="text-[0.65rem] font-bold tracking-[0.2em] uppercase text-gray-500 dark:text-rose-200/60 font-mono">
          Sistem Pencatatan Inventaris
        </p>
        <p
          class="text-[0.65rem] font-bold tracking-[0.2em] uppercase text-gray-500 dark:text-rose-200/60 font-mono mt-1">
          dan Maintenance Alat Rumah Tangga
        </p>
      </div>

    </div>

    <div class="space-y-4 relative z-20">
      {{ $this->form }}
    </div>

    <div class="pt-3">
      <x-filament::button type="submit"
        class="w-full shadow-lg shadow-rose-500/20 dark:shadow-rose-900/30 hover:shadow-rose-500/40 dark:hover:shadow-rose-900/50 transition-all duration-300 hover:-translate-y-0.5">
        <span class="font-bold tracking-wider uppercase text-xs">
          Masuk
        </span>
      </x-filament::button>
    </div>

    <div class="text-center mt-6">
      <p class="text-[10px] font-medium tracking-wider text-gray-400 dark:text-gray-500 uppercase">
        &copy; {{ date('Y') }} RSU MIPA YK. All rights reserved.
      </p>
    </div>

  </form>
</div>