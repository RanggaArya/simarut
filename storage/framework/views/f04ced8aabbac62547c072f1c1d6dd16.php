<?php
    /** @var \App\Models\Peminjaman|null $record */
    $record = $getRecord();
    $status = $record?->status;

    $statusConfig = match ($status) {
        'Menunggu' => ['color' => 'text-slate-200', 'bg' => 'bg-slate-500/20', 'border' => 'border-slate-500/30', 'icon' => 'heroicon-m-clock'],
        'Dipinjam' => ['color' => 'text-amber-200', 'bg' => 'bg-amber-500/20', 'border' => 'border-amber-500/30', 'icon' => 'heroicon-m-arrow-right-start-on-rectangle'],
        'Dikembalikan' => ['color' => 'text-emerald-200', 'bg' => 'bg-emerald-500/20', 'border' => 'border-emerald-500/30', 'icon' => 'heroicon-m-check-circle'],
        'Terlambat' => ['color' => 'text-rose-200', 'bg' => 'bg-rose-500/20', 'border' => 'border-rose-500/30', 'icon' => 'heroicon-m-exclamation-triangle'],
        'Ditolak' => ['color' => 'text-rose-200', 'bg' => 'bg-rose-500/20', 'border' => 'border-rose-500/30', 'icon' => 'heroicon-m-x-circle'],
        default => ['color' => 'text-gray-200', 'bg' => 'bg-gray-500/20', 'border' => 'border-gray-500/30', 'icon' => 'heroicon-m-question-mark-circle'],
    };

    $title = $record?->nama_barang ?: 'Detail Peminjaman';
    $invNumber = $record?->nomor_inventaris ?? '-';
    $merk = $record?->merk ?? '-';

    $start = optional($record?->tanggal_mulai)->format('d M Y');
    $end   = optional($record?->tanggal_selesai)->format('d M Y');
    $range = ($start || $end) ? "{$start} - {$end}" : '-';
?>

<div class="group relative overflow-hidden rounded-3xl border border-gray-200 bg-slate-900 shadow-lg ring-1 ring-white/10 dark:border-white/5">
    
    <div class="absolute inset-0 z-0">
        <div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-primary-500/20 blur-[100px] opacity-40"></div>
        <div class="absolute -left-20 bottom-0 h-64 w-64 rounded-full bg-blue-500/10 blur-[80px] opacity-30"></div>
        
        <svg class="absolute inset-0 h-full w-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern" width="32" height="32" patternUnits="userSpaceOnUse">
                    <path d="M0 32V.5H32" fill="none" stroke="currentColor" stroke-width="1" class="text-white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)" />
        </svg>
    </div>

    <div class="relative z-10 p-6 md:p-8">
        
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            
            <div class="flex items-start gap-5">
                <div class="relative flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-white/10 to-white/5 ring-1 ring-white/20 backdrop-blur-sm shadow-inner">
                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-o-cube','class' => 'h-8 w-8 text-white/90']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-o-cube','class' => 'h-8 w-8 text-white/90']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center rounded-md bg-white/10 px-2 py-0.5 text-xs font-medium text-white/70 ring-1 ring-inset ring-white/10">
                            Inventaris: <?php echo e($invNumber); ?>

                        </span>
                        <span class="inline-flex items-center rounded-md bg-white/10 px-2 py-0.5 text-xs font-medium text-white/70 ring-1 ring-inset ring-white/10">
                            <?php echo e($merk); ?>

                        </span>
                    </div>
                    
                    <h2 class="text-2xl font-bold tracking-tight text-white md:text-3xl">
                        <?php echo e($title); ?>

                    </h2>
                    
                    <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-400">
                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-calendar-days','class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-calendar-days','class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                        Periode: <span class="text-slate-200 font-medium"><?php echo e($range); ?></span>
                    </p>
                </div>
            </div>

            <div class="self-start">
                <div class="inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm font-semibold shadow-sm backdrop-blur-md <?php echo e($statusConfig['bg']); ?> <?php echo e($statusConfig['border']); ?> <?php echo e($statusConfig['color']); ?>">
                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => $statusConfig['icon'],'class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statusConfig['icon']),'class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                    <?php echo e($status ?? 'Draft'); ?>

                </div>
            </div>
        </div>

        <div class="my-8 border-t border-white/10"></div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            
            <div class="group/card rounded-xl border border-white/5 bg-white/5 p-4 transition hover:bg-white/10">
                <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-user','class' => 'h-4 w-4 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-user','class' => 'h-4 w-4 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                    Peminjam
                </div>
                <div class="font-semibold text-white">
                    <?php echo e($record?->pihak_kedua_nama ?? '-'); ?>

                </div>
                <div class="text-sm text-slate-400 truncate">
                    <?php echo e($record?->peminjam_email ?? '-'); ?>

                </div>
            </div>

            <div class="group/card rounded-xl border border-white/5 bg-white/5 p-4 transition hover:bg-white/10">
                <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-clipboard-document-check','class' => 'h-4 w-4 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-clipboard-document-check','class' => 'h-4 w-4 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                    Kondisi Terakhir
                </div>
                <div class="font-semibold text-white">
                    <?php echo e($record?->kondisi_terakhir ?? '-'); ?>

                </div>
                <div class="text-xs text-slate-500 mt-1">
                   Update: <?php echo e(optional($record?->updated_at)->diffForHumans()); ?>

                </div>
            </div>

            <div class="group/card rounded-xl border border-white/5 bg-white/5 p-4 transition hover:bg-white/10">
                <div class="mb-2 flex items-center gap-2 text-xs font-medium uppercase tracking-wider text-slate-400">
                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-bell-alert','class' => 'h-4 w-4 opacity-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-bell-alert','class' => 'h-4 w-4 opacity-50']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                    Reminder H-3
                </div>
                 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($record?->reminder_h3_sent_at): ?>
                    <div class="flex items-center gap-2 text-emerald-400 font-medium text-sm">
                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-check','class' => 'h-4 w-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-check','class' => 'h-4 w-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                        Terikirim
                    </div>
                    <div class="text-xs text-slate-500">
                        <?php echo e($record->reminder_h3_sent_at->format('d M, H:i')); ?>

                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-500"></span>
                        Belum dikirim
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! blank($record?->alasan_pinjam)): ?>
            <div class="mt-4 rounded-xl border border-white/5 bg-white/[0.02] p-4 text-sm leading-relaxed text-slate-300">
                <span class="mb-1 block text-xs font-bold text-slate-500 uppercase">Alasan Peminjaman:</span>
                "<?php echo e($record->alasan_pinjam); ?>"
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
</div><?php /**PATH D:\Informatika V2\INVENTARIS MIPA\Inven-RT\simarut_lastest\resources\views/infolists/peminjaman/header-modern.blade.php ENDPATH**/ ?>