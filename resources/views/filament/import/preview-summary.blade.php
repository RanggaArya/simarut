@php
    $dupes = $this->dupes;
    $count = count($dupes);
@endphp

@if($count > 0)
    <div class="rounded-xl border border-warning-500/50 bg-warning-50 dark:bg-warning-500/10 p-4 mb-4">
        <div class="flex items-start gap-3">
            <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-warning-600 dark:text-warning-500 mt-0.5" />
            <div class="flex-1">
                <h3 class="font-bold text-warning-700 dark:text-warning-500">
                    Ditemukan {{ $count }} Nomor Inventaris Duplikat
                </h3>
                <p class="text-sm text-warning-700/80 dark:text-warning-400 mt-1">
                    Nomor inventaris berikut sudah ada di database.
                    @if(($this->data['policy'] ?? 'skip') === 'skip')
                        Data ini akan <strong>dilewati (skip)</strong> secara otomatis.
                    @elseif(($this->data['policy'] ?? 'skip') === 'overwrite')
                        Data ini akan <strong>ditimpa (update)</strong> dengan data baru dari Excel.
                    @else
                        Silakan pilih item di bawah yang ingin Anda timpa.
                    @endif
                </p>
            </div>
        </div>
    </div>
@else
    <div
        class="rounded-xl border border-success-500/50 bg-success-50 dark:bg-success-500/10 p-4 mb-4 flex items-center gap-3">
        <x-heroicon-o-check-circle class="h-6 w-6 text-success-600 dark:text-success-500" />
        <div>
            <h3 class="font-bold text-success-700 dark:text-success-500">
                Aman! Tidak ada duplikat ditemukan.
            </h3>
            <p class="text-sm text-success-700/80 dark:text-success-400">
                Semua data akan diimpor sebagai data baru.
            </p>
        </div>
    </div>
@endif