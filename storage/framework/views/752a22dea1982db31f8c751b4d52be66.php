<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildSchema()); ?>

</div>
<?php /**PATH D:\Informatika V2\INVENTARIS MIPA\Inven-RT\simarut_lastest\vendor\filament\schemas\resources\views/components/grid.blade.php ENDPATH**/ ?>