<x-filament-panels::page>
    {{-- Tampilkan form utama (Upload, Scan, Settings) --}}
    {{ $this->form }}

    {{-- Loading Indicator saat proses berat --}}
    <div wire:loading wire:target="scanFile, runImport"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-xl flex items-center gap-3">
            <x-filament::loading-indicator class="h-6 w-6 text-primary-600" />
            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Sedang memproses...</span>
        </div>
    </div>
</x-filament-panels::page>