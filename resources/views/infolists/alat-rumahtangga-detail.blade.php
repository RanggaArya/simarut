<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">

  <div class="bg-gradient-to-r from-blue-400 to-indigo-900 p-6 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-white/10 blur-lg"></div>

    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h2 class="text-2xl font-bold tracking-tight text-white">
          {{ $record->nama_perangkat ?? 'Nama Tidak Diketahui' }}
        </h2>
        <div class="flex items-center gap-3 mt-2 text-blue-100 text-sm font-medium">
          <span class="bg-white/20 px-2 py-1 rounded text-xs backdrop-blur-sm border border-white/20">
            INV: {{ $record->nomor_inventaris ?? '-' }}
          </span>
          <span class="flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
              </path>
            </svg>
            {{ $record->kategori->nama_kategori ?? '-' }}
          </span>
        </div>
      </div>

      <div class="flex flex-row gap-2">
        <div
          class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg px-4 py-2 text-center shadow-sm min-w-[100px]">
          <span class="block text-[10px] text-blue-100 uppercase tracking-wider">Kondisi</span>
          <span class="text-sm font-bold text-white flex items-center justify-center gap-2"
            style="text-transform: capitalize !important;">
            @if(strtolower($record->kondisi->nama_kondisi ?? '') == 'baik')
              <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            @else
              <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
            @endif
            {{ $record->kondisi->nama_kondisi ?? '-' }}
          </span>
        </div>

        <div
          class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg px-4 py-2 text-center shadow-sm min-w-[100px]">
          <span class="block text-[10px] text-blue-100 uppercase tracking-wider">Status</span>
          <span class="text-sm font-bold text-white flex items-center justify-center gap-2"
            style="text-transform: capitalize !important;">
            @if(strtolower($record->status->nama_status ?? '') == 'aktif')
              <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            @elseif(strtolower($record->status->nama_status ?? '') == 'rusak')
              <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
            @else
              <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
            @endif
            {{ $record->status->nama_status ?? '-' }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-b border-gray-100 dark:border-gray-700">

    <!-- LOKASI -->
    <div
      class="p-6 border-b md:border-b-0 md:border-r last:md:border-r-0 border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">

      <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">

        <!-- ICON WRAPPER -->
        <span class="p-1.5 rounded-md bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 21s-6-5.686-6-10a6 6 0 1112 0c0 4.314-6 10-6 10z" />
            <circle cx="12" cy="11" r="2.5" />
          </svg>
        </span>

        Lokasi Penempatan
      </span>

      <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $record->lokasi->nama_lokasi ?? '-' }}
      </p>
    </div>

    <!-- JENIS PERANGKAT -->
    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">

      <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">

        <span class="p-1.5 rounded-md bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <rect x="9" y="9" width="6" height="6" rx="1" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 10h2M3 14h2M10 3v2M14 3v2M21 10h-2M21 14h-2M10 21v-2M14 21v-2" />
          </svg>
        </span>

        Jenis Perangkat
      </span>

      <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $record->jenis->nama_jenis ?? '-' }}
      </p>
    </div>

    <!-- MEREK PERANGKAT -->
    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">

      <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">

        <span class="p-1.5 rounded-md bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
        </span>

        Merek Perangkat
      </span>

      <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $record->merek_alat ?? '-' }}
      </p>
    </div>

  </div>

  <div class="p-6">
    <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3 mb-5">
      <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
        </path>
      </svg>
      <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
        Masa Pakai & Penyusutan
      </h3>
    </div>

    @php
      $basis_harga = $record->harga_total ?? $record->harga_beli ?? 0;
      $mp = $record->masa_pakai_aktif;
      $penyusutan_per_bulan = ($record->is_kena_penyusutan && $mp > 0) ? ($basis_harga / $mp) : 0;

      // Kalkulasi persentase untuk progress bar masa pakai
      $persentase_terpakai = 0;
      if ($record->is_kena_penyusutan && $mp > 0) {
        $persentase_terpakai = min(100, round(($record->bulan_terpakai / $mp) * 100));
      }
    @endphp

    <div
      class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4 mb-6 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
      <div>
        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal Pengadaan</p>
        <p class="font-medium text-gray-900 dark:text-gray-200 text-sm">
          {{ $record->tanggal_pengadaan ? \Carbon\Carbon::parse($record->tanggal_pengadaan)->translatedFormat('d F Y') : '-' }}
        </p>
      </div>
      <div>
        <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Sumber Pendanaan</p>
        <p class="font-medium text-gray-900 dark:text-gray-200 text-sm">{{ $record->sumber_pendanaan ?? '-' }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

      <div
        class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10 dark:opacity-5">
          <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.32-.98-2.39-1.75H7.81c.14 1.62 1.25 2.83 3.09 3.14V19h2.33v-1.67c1.55-.31 2.85-1.36 2.85-2.93-.01-2.01-1.67-2.79-3.77-3.26z">
            </path>
          </svg>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nilai Perolehan</p>
        <p class="text-xl font-bold text-gray-900 dark:text-white">
          Rp {{ number_format($record->harga_total ?? $record->harga_beli, 0, ',', '.') }}
        </p>
        <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
          <span>*Biaya awal: Rp {{ number_format($record->harga_beli, 0, ',', '.') }}</span>
        </p>
      </div>

      <div
        class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10 dark:opacity-5 text-orange-500">
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6">
            </path>
          </svg>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Depresiasi / Bulan</p>

        @if(!$record->is_kena_penyusutan)
          <p class="text-xl font-bold text-gray-400 dark:text-gray-500">-</p>
          <p class="text-[10px] text-gray-400 mt-2">Tidak dikenakan penyusutan</p>
        @elseif($record->sisa_masa_pakai <= 0)
          <p class="text-xl font-bold text-gray-400 line-through decoration-red-500/50">
            Rp {{ number_format($penyusutan_per_bulan, 0, ',', '.') }}
          </p>
          <p class="text-[10px] text-red-500 mt-2 font-medium flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg> Penyusutan telah berakhir
          </p>
        @else
          <p class="text-xl font-bold text-orange-500 dark:text-orange-400">
            - Rp {{ number_format($penyusutan_per_bulan, 0, ',', '.') }}
          </p>
          <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
            <svg class="w-3 h-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg> Aktif menyusut tiap bulan
          </p>
        @endif
      </div>

      @php
        $residuBg = !$record->is_kena_penyusutan ? 'bg-gray-50 dark:bg-gray-800' : ($record->harga_residu <= 0 ? 'bg-red-50 dark:bg-red-900/10 border-red-100 dark:border-red-900/30' : 'bg-emerald-50 dark:bg-emerald-900/10 border-emerald-100 dark:border-emerald-900/30');
      @endphp

      <div
        class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden {{ $residuBg }}">
        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1">Nilai Aset Saat
          Ini</p>

        @if(!$record->is_kena_penyusutan)
          <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Rp {{ number_format($record->harga_total ?? $record->harga_beli ?? 0, 0, ',', '.') }}
          </p>
          <p class="text-[10px] text-gray-500 mt-2 leading-tight">
            Aset Ekstrakomptabel. Nilai tetap utuh.
          </p>
        @elseif($record->harga_residu <= 0)
          <p class="text-2xl font-black text-red-600 dark:text-red-400">Rp 0</p>
          <p class="text-[10px] text-red-600/70 dark:text-red-400/70 mt-2 leading-tight">
            *Aset sudah disusutkan penuh.
          </p>
        @else
          <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
            Rp {{ number_format($record->harga_residu, 0, ',', '.') }}
          </p>
          <p class="text-[10px] text-emerald-600/70 dark:text-emerald-400/70 mt-2 leading-tight">
            *Total penyusutan: Rp {{ number_format($record->total_penyusutan, 0, ',', '.') }}
          </p>
        @endif
      </div>
    </div>

    <div class="p-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
      <div class="flex justify-between items-end mb-2">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Masa Pakai Aset</span>

        @if(!$record->is_kena_penyusutan)
          <span
            class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500 text-[10px] font-bold px-2.5 py-1 rounded-md border border-yellow-200 dark:border-yellow-800 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
              </path>
            </svg>
            Aset Ekstrakomptabel (&le; Rp 2 Juta)
          </span>
        @elseif($record->sisa_masa_pakai <= 0)
          <span class="text-red-600 dark:text-red-400 font-bold text-sm">Habis (0 Bulan)</span>
        @else
          <span class="text-blue-600 dark:text-blue-400 font-bold text-sm">{{ $record->sisa_masa_pakai }} Bulan
            Tersisa</span>
        @endif
      </div>

      @if($record->is_kena_penyusutan)
        <div
          class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 mb-2 overflow-hidden border border-gray-200 dark:border-gray-600">
          <div
            class="{{ $record->sisa_masa_pakai <= 0 ? 'bg-red-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }} h-3 transition-all duration-500 ease-out relative"
            style="width: {{ $persentase_terpakai }}%">
            <div class="absolute top-0 left-0 bottom-0 right-0 bg-white/20"
              style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;">
            </div>
          </div>
        </div>
        <div class="flex justify-between text-[11px] font-medium text-gray-500 dark:text-gray-400">
          <span>Telah terpakai: <strong class="text-gray-800 dark:text-gray-200">{{ $record->bulan_terpakai }}
              Bulan</strong> ({{ $persentase_terpakai }}%)</span>
          <span>Total: <strong class="text-gray-800 dark:text-gray-200">{{ $record->masa_pakai_aktif }}
              Bulan</strong></span>
        </div>
      @else
        <div
          class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-center border border-dashed border-gray-300 dark:border-gray-600 mt-2">
          <p class="text-xs text-gray-500 dark:text-gray-400">Karena nilai perolehan di bawah batas kapitalisasi, aset ini
            tidak memiliki target batas umur ekonomis bulanan.</p>
        </div>
      @endif
    </div>
  </div>

  <div class="p-6 bg-gray-50/50 dark:bg-gray-800/30 border-t border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
          </path>
        </svg>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
          Riwayat & Jadwal Depresiasi
        </h3>
      </div>
      @if($record->is_kena_penyusutan && $record->bulan_terpakai > 0)
        <span
          class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-bold px-2.5 py-1 rounded-full">
          {{ min($record->bulan_terpakai, $record->masa_pakai_aktif) }} Bulan Tercatat
        </span>
      @endif
    </div>

    @php
      $history = [];
      if ($record->is_kena_penyusutan && $record->masa_pakai_aktif > 0 && $record->tanggal_pengadaan) {
        $basis_harga = $record->harga_total ?? $record->harga_beli ?? 0;
        $penyusutan_per_bulan = $basis_harga / $record->masa_pakai_aktif;
        $limit = min($record->bulan_terpakai, $record->masa_pakai_aktif);

        $current_value = $basis_harga;
        $start_date = \Carbon\Carbon::parse($record->tanggal_pengadaan)->startOfMonth();

        for ($i = 1; $i <= $limit; $i++) {
          $period_start = $start_date->copy()->addMonths($i - 1);
          $period_end = $start_date->copy()->addMonths($i);
          $current_value -= $penyusutan_per_bulan;

          $history[] = [
            'bulan_ke' => $i,
            'periode' => $period_start->translatedFormat('M Y') . ' - ' . $period_end->translatedFormat('M Y'),
            'depresiasi' => $penyusutan_per_bulan,
            'nilai_aset' => max(0, round($current_value))
          ];
        }
      }
    @endphp

    @if(!$record->is_kena_penyusutan)
      <div
        class="flex flex-col items-center justify-center py-8 px-4 text-center border border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800">
        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4m8-8v16"></path>
        </svg>
        <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Tidak Ada Riwayat Penyusutan</p>
        <p class="text-xs text-gray-500 mt-1">Aset ini berada di bawah batas kapitalisasi (Ekstrakomptabel).</p>
      </div>
    @elseif(count($history) === 0)
      <div
        class="flex flex-col items-center justify-center py-8 px-4 text-center border border-dashed border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800">
        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-sm font-medium text-gray-900 dark:text-gray-200">Penyusutan Belum Berjalan</p>
        <p class="text-xs text-gray-500 mt-1">Umur aset sejak tanggal pengadaan belum mencapai 1 bulan penuh.</p>
      </div>
    @else
      <div
        class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-900 shadow-sm">
        <div class="max-h-[320px] overflow-y-auto custom-scrollbar relative">
          <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead
              class="sticky top-0 z-10 bg-gray-100/95 dark:bg-gray-800/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 shadow-sm">
              <tr>
                <th scope="col" class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 w-24 text-center">Bulan Ke
                </th>
                <th scope="col" class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300">Periode</th>
                <th scope="col" class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 text-right">Harga
                  Depresiasi</th>
                <th scope="col" class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-300 text-right">Nilai Aset
                  (Residu)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
              @foreach($history as $row)
                @php
                  // Deteksi apakah ini baris terakhir
                  $isLast = $loop->last;
                  // Deteksi apakah nilainya sudah 0 (Lunas)
                  $isSelesai = $row['nilai_aset'] <= 0;

                  // Warna default (Baris biasa)
                  $rowClass = 'hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group';
                  $badgeClass = 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/50 group-hover:text-indigo-600 dark:group-hover:text-indigo-400';

                  // Jika ini baris terakhir, ubah warnanya sesuai kondisi
                  if ($isLast) {
                    if ($isSelesai) {
                      // Warna Merah Tipis (Sudah Selesai/Lunas)
                      $rowClass = 'bg-red-50/60 hover:bg-red-100/60 dark:bg-red-900/20 dark:hover:bg-red-900/30 group';
                      $badgeClass = 'bg-red-200 dark:bg-red-800/50 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-700';
                    } else {
                      // Warna Hijau Tipis (Masih Berjalan/Bulan Ini)
                      $rowClass = 'bg-emerald-50/60 hover:bg-emerald-100/60 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 group';
                      $badgeClass = 'bg-emerald-200 dark:bg-emerald-800/50 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-700 animate-pulse';
                    }
                  }
                @endphp

                <tr class="{{ $rowClass }}">
                  <td class="px-4 py-2.5 text-center relative">
                    {{-- Indikator "Bulan Ini" jika masih berjalan --}}
                    @if($isLast && !$isSelesai)
                      <span class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-r"></span>
                    @elseif($isLast && $isSelesai)
                      <span class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 rounded-r"></span>
                    @endif

                    <span
                      class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-colors {{ $badgeClass }}">
                      {{ $row['bulan_ke'] }}
                    </span>
                  </td>
                  <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300 font-medium">
                    {{ $row['periode'] }}
                    @if($isLast && !$isSelesai)
                      <span
                        class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        Bulan Ini
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-2.5 text-right font-medium text-orange-500 dark:text-orange-400">
                    - Rp {{ number_format($row['depresiasi'], 0, ',', '.') }}
                  </td>
                  <td
                    class="px-4 py-2.5 text-right font-bold {{ $row['nilai_aset'] == 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                    Rp {{ number_format($row['nilai_aset'], 0, ',', '.') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @if($record->bulan_terpakai >= $record->masa_pakai_aktif)
        <div
          class="mt-3 flex items-start gap-1.5 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30">
          <svg class="w-4 h-4 text-green-600 dark:text-green-500 mt-0.5" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-xs font-medium text-green-800 dark:text-green-400">
            Penyusutan aset telah selesai. Aset ini sudah mencapai batas akhir umur ekonomisnya dan tercatat dengan nilai
            residu Rp 0.
          </p>
        </div>
      @endif
    @endif
  </div>

  <div class="p-6">
    <h3
      class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
      Supervisi
    </h3>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4">
      <div>
        <p class="text-xs text-gray-500 mb-1">Tanggal Supervisi Terakhir</p>
        <p
          class="font-medium text-sm
            {{ $record->tanggal_supervisi ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">

          {{ $record->tanggal_supervisi
  ? \Carbon\Carbon::parse($record->tanggal_supervisi)->translatedFormat('d F Y')
  : 'Belum Supervisi'
            }}
        </p>
      </div>
    </div>
  </div>

  @if($record->keterangan)
    <div class="px-6 pb-6">
      <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
        <h4 class="text-xs font-bold text-yellow-800 dark:text-yellow-500 uppercase mb-2 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Catatan Tambahan
        </h4>
        <p class="text-sm text-gray-700 dark:text-gray-300 italic">
          "{{ $record->keterangan }}"
        </p>
      </div>
    </div>
  @endif

  <div
    class="bg-gray-50 dark:bg-gray-900 px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center text-xs text-gray-400">
    <div>
      Dibuat: <span class="font-medium">{{ $record->created_at?->format('d M Y H:i') }}</span>
    </div>
    <div>
      Update: <span class="font-medium">{{ $record->updated_at?->format('d M Y H:i') }}</span>
    </div>
  </div>
</div>