<div class="w-full">
  <form wire:submit="authenticate" class="space-y-6 w-full">
    <div class="flex items-center gap-4 pb-2">
      <img src="{{ asset('img/rsumpyk.png') }}"
        class="w-12 h-12 rounded-2xl border border-white/10 dark:border-white/10">

      <div class="leading-tight">
        <h1 class="font-semibold text-2xl tracking-tight text-slate-900 dark:text-white">
          SIMARUT
        </h1>
        <p class="text-sm text-slate-600 dark:text-white/70">
          Sistem Pencatatan Inventaris & Maintenance Alat Rumah Tangga
        </p>
      </div>
    </div>

    {{ $this->form }}

    <div class="pt-1 space-y-4">
      <x-filament::button type="submit" class="w-full">
        Login
      </x-filament::button>

    </div>
  </form>
</div>