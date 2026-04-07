<div class="w-full">
  <form wire:submit="authenticate" class="space-y-6 w-full">
    <div class="flex items-center gap-4 pb-2">
      <img src="<?php echo e(asset('img/rsumpyk.png')); ?>"
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

    <?php echo e($this->form); ?>


    <div class="pt-1 space-y-4">
      <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'submit','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'w-full']); ?>
        Login
       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>

    </div>
  </form>
</div><?php /**PATH D:\Informatika V2\INVENTARIS MIPA\Inven-RT\simarut_lastest\resources\views/filament/pages/login.blade.php ENDPATH**/ ?>