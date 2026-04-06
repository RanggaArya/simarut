<div
  class="relative flex items-center h-[4.5rem] px-4 transition-all duration-500 ease-[cubic-bezier(0.4,0,0.2,1)] bg-transparent"
  :class="$store.sidebar.isOpen ? 'justify-between' : 'justify-center'"
  x-data
  x-transition:enter="transition ease-out duration-500 delay-100"
  x-transition:enter-start="opacity-0 -translate-y-2"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-300"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 translate-y-2">

  <style>
      /* 1. Kaca Berjalan (Sweeping Shine) */
      @keyframes sapuKaca {
          0% { left: -100%; opacity: 0; }
          10% { opacity: 1; }
          30% { left: 200%; opacity: 0; }
          100% { left: 200%; opacity: 0; }
      }
      .kaca-sultan {
          background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent) !important;
          transform: skewX(-25deg);
          animation: sapuKaca 5s infinite !important;
      }
      .dark .kaca-sultan { background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent) !important; }

      /* 2. Air Mengalir pada Teks SIMARUT */
      @keyframes airMengalir {
          0% { background-position: 0% 50%; }
          50% { background-position: 100% 50%; }
          100% { background-position: 0% 50%; }
      }
      .teks-air {
          background-size: 200% 200% !important;
          animation: airMengalir 4s ease infinite !important;
      }

      /* 3. Logo Melayang di Air */
      @keyframes logoMelayang {
          0%, 100% { transform: translateY(0); }
          50% { transform: translateY(-4px); }
      }
      .logo-terbang {
          animation: logoMelayang 3s ease-in-out infinite !important;
      }

      /* 4. Aura Bernapas */
      @keyframes cahayaNapas {
          0% { opacity: 0.2; transform: scale(0.9); }
          100% { opacity: 0.6; transform: scale(1.2); }
      }
      .cahaya-napas {
          animation: cahayaNapas 2s alternate infinite ease-in-out !important;
      }
      
  </style>

  <div class="absolute inset-0 pointer-events-none overflow-hidden">
      <div class="absolute -top-6 -left-6 w-24 h-24 bg-primary-500/20 blur-3xl rounded-full cahaya-napas"></div>
      <div class="kaca-sultan absolute top-0 w-1/2 h-full"></div>
  </div>

  <a
    href="/"
    class="flex items-center gap-3 group outline-none relative z-10"
    x-show="$store.sidebar.isOpen"
    x-transition:enter="transition ease-out duration-400 delay-150"
    x-transition:enter-start="opacity-0 -translate-x-4 scale-95"
    x-transition:enter-end="opacity-100 translate-x-0 scale-100">
    
    <div class="relative flex items-center justify-center w-11 h-11 transition-all duration-500 group-hover:scale-110 hover:rotate-3 shrink-0">
      <div class="absolute inset-0 bg-primary-500/20 rounded-full blur-xl group-hover:blur-2xl transition-all cahaya-napas"></div>
      <img
        src="<?php echo e(asset('img/rsumpyk.png')); ?>"
        alt="Logo"
        class="relative w-full h-full object-contain drop-shadow-lg logo-terbang">
    </div>

    <div class="flex flex-col min-w-0 pr-2 pt-0.5">
      <span class="text-xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 via-primary-500 to-slate-900 dark:from-slate-100 dark:via-primary-400 dark:to-slate-100 leading-none teks-air whitespace-nowrap pb-1">
        <?php echo e(config('app.name')); ?>

      </span>
      
      <span class="text-[0.55rem] font-medium tracking-[0.15em] uppercase text-slate-500 dark:text-slate-400 font-mono mt-0.5 whitespace-nowrap">
        INVENTARIS ALAT
      </span>
      <span class="text-[0.55rem] font-medium tracking-[0.15em] uppercase text-slate-500 dark:text-slate-400 font-mono mt-0.5 whitespace-nowrap">
        RUMAH TANGGA
      </span>
    </div>
  </a>

  <button
    x-on:click="$store.sidebar.isOpen = !$store.sidebar.isOpen; $nextTick(() => { window.dispatchEvent(new CustomEvent('sidebar-toggle')) })"
    type="button"
    class="relative z-10 flex items-center justify-center transition-all duration-400 p-2 rounded-xl group focus:outline-none focus:ring-2 focus:ring-primary-500/30 shrink-0"
    :class="$store.sidebar.isOpen 
            ? 'w-10 h-10 text-slate-500 hover:text-primary-500 dark:text-slate-400 dark:hover:text-primary-400' 
            : 'w-full h-full bg-transparent'">
    
    <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-chevron-double-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.265,1.55)] group-hover:-translate-x-1','x-show' => '$store.sidebar.isOpen','x-transition:enter' => 'transition-transform duration-500 ease-out','x-transition:enter-start' => '-translate-x-2','x-transition:enter-end' => 'translate-x-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>

    <div
      x-show="!$store.sidebar.isOpen"
      x-transition:enter="transition-all duration-500 delay-100 ease-out"
      x-transition:enter-start="opacity-0 scale-75"
      x-transition:enter-end="opacity-100 scale-100"
      class="relative flex flex-col items-center justify-center w-full h-full gap-1">
      
      <div class="relative w-9 h-9 transition-all duration-500 group-hover:scale-110">
        <img src="<?php echo e(asset('img/rsumpyk.png')); ?>" alt="Logo" class="w-full h-full object-contain logo-terbang drop-shadow-md">
      </div>
      
      <div class="absolute inset-0 flex items-center justify-center transition-all duration-500 scale-0 opacity-0 group-hover:scale-100 group-hover:opacity-100">
        <div class="p-2 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md rounded-lg shadow-xl ring-1 ring-primary-500/50">
          <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-chevron-double-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5 text-primary-600 dark:text-primary-400 animate-pulse']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
        </div>
      </div>
    </div>
  </button>
</div><?php /**PATH D:\Informatika V2\INVENTARIS MIPA\Inven-RT\simarut_lastest\resources\views/filament/components/sidebar-header.blade.php ENDPATH**/ ?>