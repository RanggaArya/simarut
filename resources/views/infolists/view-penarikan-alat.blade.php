@php
  $record = $this->getRecord();
  // Pastikan alasan penarikan berupa array (jika disimpan sebagai JSON/cast)
  $alasan = $record->alasan_penarikan;
  if (!is_array($alasan)) {
    $alasan = json_decode($alasan, true) ?? [];
  }
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

  {{-- KOLOM KIRI: Informasi Perangkat (Asset Card) --}}
  <div class="col-span-1 space-y-6">
    <div
      class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden relative">

      {{-- Decorative Header Background --}}
      <div class="h-24 bg-gradient-to-br from-primary-500 to-primary-700 relative">
        <div class="absolute bottom-0 left-0 p-4 w-full">
          <div class="bg-white/20 backdrop-blur-md text-white text-xs font-bold px-2 py-1 rounded inline-block">
            INVENTARIS ASET
          </div>
        </div>
      </div>

      {{-- Asset Content --}}
      <div class="p-6 pt-2">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
              {{ $record->nama_perangkat }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 font-mono mt-1">
              #{{ $record->nomor_inventaris }}
            </p>
          </div>
        </div>

        <div class="space-y-4">
          <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-lg">
            <x-heroicon-m-computer-desktop class="w-5 h-5 text-gray-400" />
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold">Merek</p>
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->merek ?? '-' }}</p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-lg">
            <x-heroicon-m-map-pin class="w-5 h-5 text-gray-400" />
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold">Lokasi Terakhir</p>
              <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $record->lokasi->nama_lokasi ?? 'Lokasi tidak diketahui' }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-lg">
            <x-heroicon-m-calendar-days class="w-5 h-5 text-gray-400" />
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold">Tahun Pengadaan</p>
              <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->tahun_pengadaan ?? '-' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- KOLOM KANAN: Detail Penarikan & Tindak Lanjut --}}
  <div class="col-span-1 lg:col-span-2 space-y-6">

    {{-- Card 1: Status Penarikan --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6">
      <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-white/10 pb-4">
        <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
          <x-heroicon-o-document-minus class="w-6 h-6 text-red-600 dark:text-red-400" />
        </div>
        <div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Laporan Penarikan</h3>
          <p class="text-sm text-gray-500">Detail alasan dan tanggal penarikan alat</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Penarikan</label>
          <div class="mt-1 text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            {{ \Carbon\Carbon::parse($record->tanggal_penarikan)->translatedFormat('d F Y') }}
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditarik Oleh</label>
          <div class="mt-1 flex items-center gap-2">
            @php
              $uName = $record->user->name ?? 'System';
              $avatarSrc = $record->user->avatar_url
                ? $record->user->avatar_url
                : 'https://ui-avatars.com/api/?name=' . urlencode($uName) . '&color=FFFFFF&background=d97706&bold=true';
            @endphp

            <x-filament::avatar src="{{ $avatarSrc }}" size="xs" class="ring-1 ring-gray-200 dark:ring-white/10" />

            <span class="text-base font-medium text-gray-900 dark:text-white">
              {{ $uName }}
            </span>
          </div>
        </div>

        <div class="col-span-1 md:col-span-2">
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-2">Alasan Penarikan</label>
          <div class="flex flex-wrap gap-2">
            @forelse($alasan as $item)
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                {{ $item }}
              </span>
            @empty
              <span class="text-sm text-gray-400 italic">Tidak ada alasan spesifik dipilih</span>
            @endforelse
          </div>
        </div>

        @if($record->alasan_lainnya)
          <div
            class="col-span-1 md:col-span-2 bg-gray-50 dark:bg-white/5 p-4 rounded-lg border border-gray-100 dark:border-white/5">
            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1 block">Catatan Tambahan</label>
            <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $record->alasan_lainnya }}"</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Card 2: Tindak Lanjut --}}
    <div
      class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6 relative overflow-hidden">
      <div class="absolute top-0 right-0 p-4 opacity-10">
        <x-heroicon-o-arrow-path class="w-32 h-32 text-primary-500" />
      </div>

      <div class="relative z-10">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
            <x-heroicon-o-clipboard-document-check class="w-6 h-6 text-primary-600 dark:text-primary-400" />
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rekomendasi Tindak Lanjut</h3>
        </div>

        <div
          class="bg-primary-50/50 dark:bg-primary-900/10 border border-primary-100 dark:border-primary-900/30 rounded-xl p-5">
          <div class="flex flex-col md:flex-row gap-6 md:items-center">
            <div class="flex-shrink-0">
              <span
                class="block text-xs font-medium text-primary-600 dark:text-primary-400 uppercase mb-1">Keputusan</span>
              <span class="text-2xl font-bold text-primary-700 dark:text-primary-300">
                {{ $record->tindak_lanjut_tipe }}
              </span>
            </div>

            @if($record->tindak_lanjut_detail)
              <div class="hidden md:block w-px h-12 bg-primary-200 dark:bg-primary-800"></div>

              <div class="flex-grow">
                <span class="block text-xs font-medium text-primary-600 dark:text-primary-400 uppercase mb-1">Detail
                  Eksekusi</span>
                <p class="text-base text-gray-800 dark:text-gray-200">
                  {{ $record->tindak_lanjut_detail }}
                </p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

  </div>
</div>