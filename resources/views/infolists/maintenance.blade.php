@php
    $record = $getRecord();

    $statusColor = match($record->status_akhir) {
        'berfungsi' => 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
        'berfungsi_sebagian' => 'text-amber-700 bg-amber-50 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
        'tidak_berfungsi' => 'text-red-700 bg-red-50 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
        default => 'text-gray-700 bg-gray-50 border-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700',
    };

    $statusLabel = match($record->status_akhir) {
        'berfungsi' => 'Berfungsi Normal',
        'berfungsi_sebagian' => 'Berfungsi Sebagian',
        'tidak_berfungsi' => 'Rusak / Tidak Berfungsi',
        default => 'Status Tidak Diketahui',
    };
@endphp

<div class="flex flex-col gap-6">
    
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-gray-800/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg dark:bg-blue-900/30 dark:text-blue-400">
                    <x-heroicon-m-wrench-screwdriver class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $record->perangkats->nama_perangkat ?? 'Nama Perangkat' }}
                    </h2>
                    <p class="text-sm font-mono text-gray-500 dark:text-gray-400">
                        INV: {{ $record->perangkats->nomor_inventaris ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="px-4 py-1.5 rounded-full text-sm font-bold border {{ $statusColor }}">
                {{ $statusLabel }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Maintenance</span>
                <div class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-m-calendar class="w-4 h-4 text-gray-400"/>
                    {{ \Carbon\Carbon::parse($record->tanggal_maintenance)->isoFormat('D MMMM Y') }}
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</span>
                <div class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-m-user-circle class="w-4 h-4 text-gray-400"/>
                    {{ $record->user->name ?? 'System' }}
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi / Ruangan</span>
                <div class="font-medium text-gray-900 dark:text-white flex items-center gap-2">
                    <x-heroicon-m-map-pin class="w-4 h-4 text-gray-400"/>
                    {{ $record->lokasi->nama_lokasi ?? '-' }}
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik / User</span>
                <div class="font-medium text-gray-900 dark:text-white">
                    {{ $record->nama_pemilik ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-m-clipboard-document-list class="w-5 h-5 text-gray-400"/>
                    Detail Pengerjaan
                </h3>

                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($record->maintenanceTypes as $type)
                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                            {{ $type->nama }}
                        </span>
                    @endforeach
                </div>

                <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                    <p class="whitespace-pre-line">{{ $record->deskripsi }}</p>
                </div>

                @if($record->catatan)
                    <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/10 border-l-4 border-yellow-400 rounded-r-md">
                        <span class="text-xs font-bold text-yellow-600 dark:text-yellow-500 uppercase block mb-1">Catatan Tambahan</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $record->catatan }}"</p>
                    </div>
                @endif
            </div>

            <div class="rounded-xl border border-gray-200 bg-white overflow-hidden dark:border-gray-800 dark:bg-gray-900 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-m-cpu-chip class="w-5 h-5 text-gray-400"/>
                        Komponen & Sparepart
                    </h3>
                </div>
                
                @if($record->komponenDetails->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Komponen</th>
                                    <th class="px-6 py-3 font-medium">Aksi</th>
                                    <th class="px-6 py-3 font-medium">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach($record->komponenDetails as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $item->komponen->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->aksi === 'diganti')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                                    <x-heroicon-m-arrow-path class="w-3 h-3"/> Diganti
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                                    <x-heroicon-m-eye class="w-3 h-3"/> Dicek
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                            {{ $item->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 italic">
                        Tidak ada komponen yang diganti atau dicek secara spesifik.
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1" x-data="{ imageUrl: null, isOpen: false }">
            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 shadow-sm h-full">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-heroicon-m-photo class="w-5 h-5 text-gray-400"/>
                    Dokumentasi
                </h3>

                @if($record->foto && count($record->foto) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">
                        @foreach($record->foto as $foto)
                            <div 
                                @click="imageUrl = '{{ asset('storage/' . $foto) }}'; isOpen = true"
                                class="group relative aspect-video rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 cursor-pointer"
                            >
                                <img 
                                    src="{{ asset('storage/' . $foto) }}" 
                                    alt="Dokumentasi Maintenance" 
                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                                >
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <x-heroicon-m-magnifying-glass-plus class="w-8 h-8 text-white drop-shadow-md"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-40 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 text-gray-400">
                        <x-heroicon-m-photo class="w-8 h-8 mb-2 opacity-50"/>
                        <span class="text-xs">Tidak ada foto</span>
                    </div>
                @endif
            </div>

            <div 
                x-show="isOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                style="display: none;" 
                @keydown.escape.window="isOpen = false"
            >
                <button @click="isOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300 focus:outline-none z-50">
                    <x-heroicon-m-x-mark class="w-10 h-10"/>
                </button>

                <div @click.away="isOpen = false" class="relative max-w-7xl max-h-screen w-full h-full flex items-center justify-center">
                    <img :src="imageUrl" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl ring-1 ring-white/10">
                </div>
            </div>
        </div>

    </div>
</div>