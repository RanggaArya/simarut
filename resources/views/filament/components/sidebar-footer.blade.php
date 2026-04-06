@php
  $user = auth()->user();
@endphp

<style>
  /* Efek Menu Meluncur Halus */
  .menu-geser { transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
  .group:hover .menu-geser { transform: translateX(4px); }
  
  /* Efek Avatar Sultan (Glow Halus) */
  .avatar-sultan { transition: all 0.4s ease; }
  .group:hover .avatar-sultan { transform: scale(1.05); }
  
  /* Nama Gradient Mengkilap saat Hover */
  .nama-sultan { background-size: 200% auto; transition: all 0.3s ease; }
  .group:hover .nama-sultan {
      background-image: linear-gradient(to right, var(--primary-400), #ffffff, var(--primary-400));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: kilapVIP 3s linear infinite;
  }
  @keyframes kilapVIP { to { background-position: 200% center; } }
  /* --- EFEK SULTAN UNTUK MENU SIDEBAR FILAMENT --- */
  
  /* 1. Efek Meluncur ke Kanan saat Menu di-Hover */
  li.fi-sidebar-item {
      transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
  }
  li.fi-sidebar-item:hover {
      transform: translateX(8px) !important;
  }

  /* 2. Ikon Menu Berputar Saat di-Hover */
  li.fi-sidebar-item:hover .fi-sidebar-item-icon {
      transform: scale(1.15) rotate(-8deg) !important;
      color: rgba(var(--primary-500), 1) !important;
      transition: all 0.3s ease !important;
  }

  /* 3. Teks Menu Menyala Saat di-Hover */
  li.fi-sidebar-item:hover .fi-sidebar-item-label {
      color: rgba(var(--primary-500), 1) !important;
  }

  /* 4. PERBAIKAN MENU AKTIF (Buang kotak kaku, ganti gradasi elegan) */
  .fi-sidebar-item-active > a {
      background: linear-gradient(90deg, rgba(var(--primary-500), 0.15), transparent) !important;
      border-left: 3px solid rgba(var(--primary-500), 1) !important;
      border-radius: 0 !important; /* Menghilangkan bentuk pil kaku */
  }
  
  /* Mewarnai teks & ikon menu yang sedang aktif */
  .fi-sidebar-item-active .fi-sidebar-item-icon,
  .fi-sidebar-item-active .fi-sidebar-item-label {
      color: rgba(var(--primary-500), 1) !important;
      font-weight: bold !important;
  }
</style>

<div
  x-data="{
        isFooterOpen: JSON.parse(localStorage.getItem('isFooterOpen') ?? 'false'),
        toggleFooter() {
            this.isFooterOpen = !this.isFooterOpen;
            localStorage.setItem('isFooterOpen', this.isFooterOpen);
        }
    }"
  class="relative flex flex-col z-20 bg-transparent"
>
  <div class="w-full h-[1px] bg-gradient-to-r from-transparent via-gray-300 dark:via-gray-600 to-transparent opacity-30"></div>

  <div x-show="isFooterOpen" x-collapse style="display: none;" class="px-2 pt-3 pb-1 space-y-1">
    
    <div x-data="{
                theme: localStorage.getItem('theme') || 'system',
                setTheme(val) {
                    this.theme = val;
                    localStorage.setItem('theme', val);
                    document.documentElement.classList.toggle('dark', val === 'dark' || (val === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches));
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: val }));
                }
            }"
      class="p-1 mb-2 bg-gray-500/5 dark:bg-white/5 rounded-xl ring-1 ring-gray-500/10 dark:ring-white/5">
      <div class="flex items-center justify-between w-full">
        @foreach(['light' => 'sun', 'dark' => 'moon', 'system' => 'computer-desktop'] as $mode => $icon)
          <button @click="setTheme('{{ $mode }}')" 
            :class="theme === '{{ $mode }}' 
                    ? 'bg-white text-primary-600 shadow-sm dark:bg-white/10 dark:text-primary-400' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-500/10 dark:hover:bg-white/5'"
            class="flex-1 flex items-center justify-center py-1.5 rounded-lg transition-all duration-300 group/btn"
            title="Tema {{ ucfirst($mode) }}">
            @svg('heroicon-m-' . $icon, 'w-4 h-4 transition-transform duration-300 group-hover/btn:scale-110')
          </button>
        @endforeach
      </div>
    </div>

    <a href="{{\App\Filament\Pages\AppSettings::getUrl()}}"
      class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-600 transition-all duration-300 rounded-xl hover:bg-gradient-to-r hover:from-white/50 hover:to-transparent dark:text-gray-300 dark:hover:from-white/5 group outline-none">
      <div class="flex items-center gap-3 menu-geser">
        <x-heroicon-o-cog-6-tooth class="w-4 h-4 text-gray-400 group-hover:text-primary-500 group-hover:rotate-90 transition-all duration-500" />
        <span class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Pengaturan Aplikasi</span>
      </div>
    </a>
    
    <a href="{{\App\Filament\Pages\AccountSettings::getUrl()}}"
      class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-600 transition-all duration-300 rounded-xl hover:bg-gradient-to-r hover:from-white/50 hover:to-transparent dark:text-gray-300 dark:hover:from-white/5 group outline-none">
      <div class="flex items-center gap-3 menu-geser">
        <x-heroicon-o-user-circle class="w-4 h-4 text-gray-400 group-hover:text-primary-500 transition-all duration-300" />
        <span class="group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Pengaturan Akun</span>
      </div>
    </a>

    <form action="{{ filament()->getLogoutUrl() }}" method="post" class="pt-1 mt-1">
      @csrf
      <button type="submit"
        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-500 transition-all duration-300 rounded-xl hover:bg-gradient-to-r hover:from-red-50 hover:to-transparent dark:text-gray-400 dark:hover:from-red-500/10 group outline-none">
        <div class="flex items-center gap-3 menu-geser">
          <x-heroicon-o-arrow-left-on-rectangle
            class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition-colors duration-300" />
          <span class="group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Keluar Aplikasi</span>
        </div>
      </button>
    </form>
  </div>

  <button @click="toggleFooter()" type="button"
    class="flex items-center gap-3 px-4 py-4 transition-all duration-500 hover:bg-gradient-to-r hover:from-white/50 hover:to-transparent dark:hover:from-white/5 group text-start focus:outline-none w-full"
    :class="!$store.sidebar.isOpen && 'justify-center px-0 py-4'">
    
    <div class="relative shrink-0">
      <div class="absolute inset-0 bg-primary-500/30 rounded-full blur-md scale-0 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-500"></div>
      
      <x-filament-panels::avatar.user size="md" :user="$user"
        class="relative ring-1 ring-gray-200 dark:ring-white/10 group-hover:ring-primary-400 shadow-sm avatar-sultan bg-transparent" />
        
      <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white dark:border-gray-900 rounded-full animate-pulse"></span>
    </div>

    <div x-show="$store.sidebar.isOpen" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="flex items-center justify-between flex-1 min-w-0">
      <div class="flex flex-col truncate pr-2">
        <span class="text-sm font-bold text-gray-900 truncate dark:text-gray-100 nama-sultan">
          {{ $user->name }}
        </span>
        <span class="text-[0.65rem] font-medium tracking-widest uppercase text-gray-500 truncate dark:text-gray-400 font-mono mt-0.5 group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors duration-300">
          {{ $user->role ?? 'Administrator' }}
        </span>
      </div>

      <div class="p-1 rounded-full group-hover:bg-black/5 dark:group-hover:bg-white/5 transition-colors duration-300 shrink-0">
          <x-heroicon-m-chevron-up class="w-4 h-4 text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-transform duration-500"
            x-bind:class="isFooterOpen && 'rotate-180'" />
      </div>
    </div>
  </button>
</div>