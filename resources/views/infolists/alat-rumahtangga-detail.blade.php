<div
  class="bg-white dark:bg-[#140b0b] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgba(225,29,72,0.05)] border border-gray-100 dark:border-rose-900/30 overflow-hidden transition-all">

  <div
    class="bg-gradient-to-br from-rose-800 via-red-950 to-black p-6 md:p-8 text-white relative overflow-hidden border-b border-rose-900/50">
    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-48 h-48 rounded-full bg-rose-500/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-1/4 w-32 h-32 rounded-full bg-red-600/10 blur-2xl"></div>
    <div
      class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-30">
    </div>

    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
      <div class="w-full md:w-auto">
        <div class="flex items-center gap-2 mb-2">
          <span class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-widest text-rose-200/80">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
              </path>
            </svg>
            {{ $record->kategori->nama_kategori ?? 'Kategori Umum' }}
          </span>
        </div>

        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white mb-4 drop-shadow-md">
          {{ $record->nama_perangkat ?? 'Nama Tidak Diketahui' }}
        </h2>

        <div
          class="inline-flex items-center gap-3 p-1.5 pr-4 bg-black/40 backdrop-blur-md rounded-xl border border-rose-500/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)]">
          <div
            class="bg-rose-600/80 text-white px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
            NO INV.
          </div>
          <span
            class="text-base md:text-lg font-mono font-bold tracking-widest text-rose-100 drop-shadow-[0_0_8px_rgba(255,228,230,0.4)]">
            {{ $record->nomor_inventaris ?? 'BELUM-ADA-NOMOR' }}
          </span>
        </div>
      </div>

      <div class="flex flex-row gap-3 self-start md:self-auto">
        <div
          class="bg-black/30 backdrop-blur-md border border-white/10 rounded-xl px-4 py-2.5 text-center shadow-lg min-w-[110px]">
          <span class="block text-[10px] text-rose-200/70 uppercase tracking-widest mb-1">Kondisi</span>
          <span class="text-sm font-bold text-white flex items-center justify-center gap-2 capitalize">
            @if(strtolower($record->kondisi->nama_kondisi ?? '') == 'baik')
              <span class="relative flex h-2.5 w-2.5"><span
                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span
                  class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
            @else
              <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_5px_rgba(251,191,36,0.5)]"></span>
            @endif
            {{ $record->kondisi->nama_kondisi ?? '-' }}
          </span>
        </div>

        <div
          class="bg-black/30 backdrop-blur-md border border-white/10 rounded-xl px-4 py-2.5 text-center shadow-lg min-w-[110px]">
          <span class="block text-[10px] text-rose-200/70 uppercase tracking-widest mb-1">Status</span>
          <span class="text-sm font-bold text-white flex items-center justify-center gap-2 capitalize">
            @if(strtolower($record->status->nama_status ?? '') == 'aktif')
              <span class="relative flex h-2.5 w-2.5"><span
                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span
                  class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
            @elseif(strtolower($record->status->nama_status ?? '') == 'rusak')
              <span class="relative flex h-2.5 w-2.5"><span
                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span><span
                  class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span></span>
            @else
              <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_5px_rgba(251,191,36,0.5)]"></span>
            @endif
            {{ $record->status->nama_status ?? '-' }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <div
    class="grid grid-cols-1 md:grid-cols-3 gap-0 border-b border-gray-100 dark:border-rose-900/20 bg-gray-50/50 dark:bg-[#160c0c]">

    <div
      class="p-6 border-b md:border-b-0 md:border-r border-gray-100 dark:border-rose-900/20 hover:bg-white dark:hover:bg-[#1a0e0e] transition duration-300">
      <span
        class="text-xs font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest flex items-center gap-2.5">
        <span class="p-1.5 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 21s-6-5.686-6-10a6 6 0 1112 0c0 4.314-6 10-6 10z" />
            <circle cx="12" cy="11" r="2.5" />
          </svg>
        </span>
        Lokasi Penempatan
      </span>
      <p class="mt-3 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $record->lokasi->nama_lokasi ?? '-' }}
      </p>
    </div>

    <div
      class="p-6 border-b md:border-b-0 md:border-r border-gray-100 dark:border-rose-900/20 hover:bg-white dark:hover:bg-[#1a0e0e] transition duration-300">
      <span
        class="text-xs font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest flex items-center gap-2.5">
        <span
          class="p-1.5 rounded-lg bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 shadow-sm">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="9" y="9" width="6" height="6" rx="1" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 10h2M3 14h2M10 3v2M14 3v2M21 10h-2M21 14h-2M10 21v-2M14 21v-2" />
          </svg>
        </span>
        Jenis Perangkat
      </span>
      <p class="mt-3 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $record->jenis->nama_jenis ?? '-' }}</p>
    </div>

    <div class="p-6 hover:bg-white dark:hover:bg-[#1a0e0e] transition duration-300">
      <span
        class="text-xs font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest flex items-center gap-2.5">
        <span class="p-1.5 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 shadow-sm">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
        </span>
        Merek Perangkat
      </span>
      <p class="mt-3 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $record->merek_alat ?? '-' }}</p>
    </div>

  </div>

  <div class="p-6 md:p-8">
    <div class="flex items-center gap-3 border-b border-gray-200 dark:border-rose-900/30 pb-4 mb-6">
      <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
          </path>
        </svg>
      </div>
      <h3 class="text-base font-extrabold text-gray-900 dark:text-rose-50 uppercase tracking-widest">
        Masa Pakai, Keuangan & Penyusutan
      </h3>
    </div>

    @php
      $basis_harga = $record->harga_total ?? $record->harga_beli ?? 0;
      $mp = $record->masa_pakai_aktif;
      $penyusutan_per_bulan = ($record->is_kena_penyusutan && $mp > 0) ? ($basis_harga / $mp) : 0;
      $persentase_terpakai = ($record->is_kena_penyusutan && $mp > 0) ? min(100, round(($record->bulan_terpakai / $mp) * 100)) : 0;
    @endphp

    <div
      class="grid grid-cols-2 md:grid-cols-3 gap-4 p-5 mb-6 rounded-xl bg-gray-50 dark:bg-[#180d0d] border border-gray-100 dark:border-rose-900/20">
      <div>
        <p class="text-[10px] font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest mb-1.5">Tanggal
          Pengadaan</p>
        <p class="font-semibold text-gray-900 dark:text-gray-200 text-sm">
          {{ $record->tanggal_pengadaan ? \Carbon\Carbon::parse($record->tanggal_pengadaan)->translatedFormat('d F Y') : '-' }}
        </p>
      </div>
      <div>
        <p class="text-[10px] font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest mb-1.5">Tahun
          Pengadaan</p>
        <p class="font-semibold text-gray-900 dark:text-gray-200 text-sm">{{ $record->tahun_pengadaan ?? '-' }}</p>
      </div>
      <div>
        <p class="text-[10px] font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest mb-1.5">Sumber
          Pendanaan</p>
        <p class="font-semibold text-gray-900 dark:text-gray-200 text-sm">{{ $record->sumber_pendanaan ?? '-' }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
      <div
        class="p-5 rounded-2xl border border-gray-200 dark:border-rose-900/30 bg-white dark:bg-gradient-to-br dark:from-[#1a0e0e] dark:to-[#120a0a] shadow-sm relative group hover:shadow-md transition-all">
        <div
          class="absolute top-0 right-0 p-4 opacity-5 text-gray-900 dark:text-white transition-opacity group-hover:opacity-10">
          <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
            <path
              d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.32-.98-2.39-1.75H7.81c.14 1.62 1.25 2.83 3.09 3.14V19h2.33v-1.67c1.55-.31 2.85-1.36 2.85-2.93-.01-2.01-1.67-2.79-3.77-3.26z">
            </path>
          </svg>
        </div>
        <p class="text-[11px] font-bold text-gray-500 dark:text-rose-200/60 uppercase tracking-widest mb-2">Nilai
          Perolehan</p>
        <p class="text-2xl font-extrabold text-gray-900 dark:text-white">Rp
          {{ number_format($record->harga_total ?? $record->harga_beli, 0, ',', '.') }}
        </p>
        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-2 font-medium">Biaya awal: Rp
          {{ number_format($record->harga_beli, 0, ',', '.') }}
        </p>
      </div>

      <div
        class="p-5 rounded-2xl border border-gray-200 dark:border-rose-900/30 bg-white dark:bg-gradient-to-br dark:from-[#1a0e0e] dark:to-[#120a0a] shadow-sm relative group hover:shadow-md transition-all">
        <div class="absolute top-0 right-0 p-4 opacity-5 text-amber-500 transition-opacity group-hover:opacity-10">
          <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6">
            </path>
          </svg>
        </div>
        <p class="text-[11px] font-bold text-gray-500 dark:text-rose-200/60 uppercase tracking-widest mb-2">Depresiasi /
          Bulan</p>

        @if(!$record->is_kena_penyusutan)
          <p class="text-2xl font-bold text-gray-400 dark:text-gray-600">-</p>
          <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-2 font-medium">Tidak dikenakan penyusutan</p>
        @elseif($record->sisa_masa_pakai <= 0)
          <p class="text-2xl font-bold text-gray-400 dark:text-gray-600 line-through decoration-red-500/50">Rp
            {{ number_format($penyusutan_per_bulan, 0, ',', '.') }}
          </p>
          <p class="text-[11px] text-rose-500 mt-2 font-bold flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none"
              stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg> Penyusutan berakhir</p>
        @else
          <p class="text-2xl font-extrabold text-amber-600 dark:text-amber-500">- Rp
            {{ number_format($penyusutan_per_bulan, 0, ',', '.') }}
          </p>
          <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-2 font-medium flex items-center gap-1"><svg
              class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg> Aktif menyusut tiap bulan</p>
        @endif
      </div>

      @php
        $residuBg = !$record->is_kena_penyusutan
          ? 'bg-gray-50 dark:bg-[#180d0d] dark:border-rose-900/20'
          : ($record->harga_residu <= 0
            ? 'bg-rose-50 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800/50'
            : 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/30');
      @endphp
      <div class="p-5 rounded-2xl border shadow-sm relative overflow-hidden {{ $residuBg }} transition-all">
        <p class="text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2">Nilai Aset Saat
          Ini</p>

        @if(!$record->is_kena_penyusutan)
          <p class="text-3xl font-black text-gray-800 dark:text-white">Rp
            {{ number_format($record->harga_total ?? $record->harga_beli ?? 0, 0, ',', '.') }}
          </p>
          <p class="text-[11px] text-gray-500 mt-2 font-medium">Aset Ekstrakomptabel (Nilai utuh).</p>
        @elseif($record->harga_residu <= 0)
          <p class="text-3xl font-black text-rose-600 dark:text-rose-500">Rp 0</p>
          <p class="text-[11px] text-rose-700/70 dark:text-rose-400/70 mt-2 font-medium">*Aset sepenuhnya disusutkan.</p>
        @else
          <p class="text-3xl font-black text-emerald-600 dark:text-emerald-500">Rp
            {{ number_format($record->harga_residu, 0, ',', '.') }}
          </p>
          <p class="text-[11px] text-emerald-700/70 dark:text-emerald-500/70 mt-2 font-medium">*Akumulasi penyusutan: Rp
            {{ number_format($record->total_penyusutan, 0, ',', '.') }}
          </p>
        @endif
      </div>
    </div>

    <div
      class="p-5 md:p-6 rounded-2xl border border-gray-200 dark:border-rose-900/30 bg-white dark:bg-[#160c0c] shadow-sm">
      <div class="flex justify-between items-end mb-3">
        <span class="text-xs font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest">Status Masa
          Pakai</span>
        @if(!$record->is_kena_penyusutan)
          <span
            class="bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500 text-[10px] font-bold px-3 py-1 rounded-md border border-amber-200 dark:border-amber-500/20 flex items-center gap-1.5 uppercase tracking-wide">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg> Ekstrakomptabel (&le; Rp 2 Juta)
          </span>
        @elseif($record->sisa_masa_pakai <= 0)
          <span class="text-rose-600 dark:text-rose-500 font-extrabold text-sm uppercase tracking-wide">Expired (0
            Bulan)</span>
        @else
          <span
            class="text-emerald-600 dark:text-emerald-500 font-extrabold text-sm uppercase tracking-wide">{{ $record->sisa_masa_pakai }}
            Bulan Tersisa</span>
        @endif
      </div>

      @if($record->is_kena_penyusutan)
        <div
          class="w-full bg-gray-100 dark:bg-black/50 rounded-full h-3.5 mb-2 overflow-hidden border border-gray-200 dark:border-rose-900/30 shadow-inner">
          <div
            class="{{ $record->sisa_masa_pakai <= 0 ? 'bg-rose-600' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }} h-full transition-all duration-1000 ease-out relative"
            style="width: {{ $persentase_terpakai }}%">
            <div
              class="absolute inset-0 bg-[linear-gradient(45deg,rgba(255,255,255,.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,.15)_50%,rgba(255,255,255,.15)_75%,transparent_75%,transparent)] bg-[length:1rem_1rem]">
            </div>
          </div>
        </div>
        <div class="flex justify-between text-[11px] font-semibold text-gray-500 dark:text-gray-400">
          <span>Terpakai: <strong class="text-gray-900 dark:text-rose-100">{{ $record->bulan_terpakai }} Bulan</strong>
            ({{ $persentase_terpakai }}%)</span>
          <span>Target Total: <strong class="text-gray-900 dark:text-rose-100">{{ $record->masa_pakai_aktif }}
              Bulan</strong></span>
        </div>
      @else
        <div
          class="w-full bg-gray-50 dark:bg-white/5 rounded-xl p-4 text-center border border-dashed border-gray-300 dark:border-rose-900/30 mt-3">
          <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Karena nilai perolehan di bawah batas
            kapitalisasi, aset ini tidak memerlukan tracking umur manfaat.</p>
        </div>
      @endif
    </div>
  </div>

  <div
    class="p-6 md:p-8 bg-gray-50/80 dark:bg-[#120a0a] border-t border-gray-200 dark:border-rose-900/30 shadow-[inset_0_4px_6px_rgba(0,0,0,0.02)]">
    <div class="flex items-center justify-between mb-5">
      <div class="flex items-center gap-3">
        <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
        </div>
        <h3 class="text-base font-extrabold text-gray-900 dark:text-rose-50 uppercase tracking-widest">
          Riwayat Depresiasi per Bulan
        </h3>
      </div>
      @if($record->is_kena_penyusutan && $record->bulan_terpakai > 0)
        <span
          class="bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 text-[10px] uppercase tracking-wider font-extrabold px-3 py-1.5 rounded-lg border border-indigo-200 dark:border-indigo-800/50">
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
        class="flex flex-col items-center justify-center py-10 px-4 text-center border border-dashed border-gray-300 dark:border-rose-900/30 rounded-2xl bg-white dark:bg-[#160c0c]">
        <div class="p-3 bg-gray-100 dark:bg-black/40 rounded-full mb-3"><svg
            class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4m8-8v16"></path>
          </svg></div>
        <p class="text-sm font-bold text-gray-900 dark:text-gray-300">Tidak Ada Riwayat Penyusutan</p>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-500 mt-1">Aset di bawah batas kapitalisasi
          (Ekstrakomptabel).</p>
      </div>
    @elseif(count($history) === 0)
      <div
        class="flex flex-col items-center justify-center py-10 px-4 text-center border border-dashed border-gray-300 dark:border-rose-900/30 rounded-2xl bg-white dark:bg-[#160c0c]">
        <div class="p-3 bg-gray-100 dark:bg-black/40 rounded-full mb-3"><svg
            class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg></div>
        <p class="text-sm font-bold text-gray-900 dark:text-gray-300">Penyusutan Belum Berjalan</p>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-500 mt-1">Umur aset sejak pengadaan belum genap 1
          bulan.</p>
      </div>
    @else
      <div
        class="border border-gray-200 dark:border-rose-900/30 rounded-2xl overflow-hidden bg-white dark:bg-[#160c0c] shadow-sm">
        <div class="max-h-[350px] overflow-y-auto custom-scrollbar relative">
          <table class="min-w-full text-left text-sm whitespace-nowrap">
            <thead
              class="sticky top-0 z-10 bg-gray-50/95 dark:bg-[#1a0e0e]/95 backdrop-blur-md border-b border-gray-200 dark:border-rose-900/50 shadow-sm">
              <tr>
                <th scope="col"
                  class="px-5 py-3.5 text-xs font-extrabold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest w-24 text-center">
                  Bulan Ke</th>
                <th scope="col"
                  class="px-5 py-3.5 text-xs font-extrabold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest">
                  Periode</th>
                <th scope="col"
                  class="px-5 py-3.5 text-xs font-extrabold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest text-right">
                  Harga Depresiasi</th>
                <th scope="col"
                  class="px-5 py-3.5 text-xs font-extrabold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest text-right">
                  Nilai Aset (Residu)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-rose-900/20">
              @foreach($history as $row)
                @php
                  $isLast = $loop->last;
                  $isSelesai = $row['nilai_aset'] <= 0;

                  $rowClass = 'hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors group';
                  $badgeClass = 'bg-gray-100 dark:bg-black/50 text-gray-600 dark:text-gray-400 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/40 group-hover:text-indigo-600 dark:group-hover:text-indigo-300';

                  if ($isLast) {
                    if ($isSelesai) {
                      $rowClass = 'bg-rose-50/80 hover:bg-rose-100/60 dark:bg-rose-950/30 dark:hover:bg-rose-950/50 group';
                      $badgeClass = 'bg-rose-200 dark:bg-rose-900 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-700';
                    } else {
                      $rowClass = 'bg-emerald-50/80 hover:bg-emerald-100/60 dark:bg-emerald-950/20 dark:hover:bg-emerald-900/20 group';
                      $badgeClass = 'bg-emerald-200 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-700 animate-pulse';
                    }
                  }
                @endphp
                <tr class="{{ $rowClass }}">
                  <td class="px-5 py-3 text-center relative">
                    @if($isLast && !$isSelesai)
                      <span class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500 rounded-r-md"></span>
                    @elseif($isLast && $isSelesai)
                      <span class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-600 rounded-r-md"></span>
                    @endif
                    <span
                      class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold transition-colors {{ $badgeClass }}">
                      {{ $row['bulan_ke'] }}
                    </span>
                  </td>
                  <td class="px-5 py-3 text-gray-700 dark:text-gray-300 font-semibold">
                    {{ $row['periode'] }}
                    @if($isLast && !$isSelesai)
                      <span
                        class="ml-2.5 inline-flex items-center px-2 py-0.5 rounded-md text-[9px] uppercase tracking-wider font-extrabold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">Bulan
                        Ini</span>
                    @endif
                  </td>
                  <td class="px-5 py-3 text-right font-bold text-amber-600 dark:text-amber-500">
                    - Rp {{ number_format($row['depresiasi'], 0, ',', '.') }}
                  </td>
                  <td
                    class="px-5 py-3 text-right font-black {{ $row['nilai_aset'] == 0 ? 'text-rose-600 dark:text-rose-500' : 'text-gray-900 dark:text-white' }}">
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
          class="mt-4 flex items-start gap-2.5 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/30 shadow-sm">
          <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-400 leading-snug">
            Penyusutan aset telah selesai secara penuh.<br>
            <span class="font-medium text-emerald-700/80 dark:text-emerald-500/80 text-xs">Aset ini sudah mencapai batas
              akhir umur manfaatnya dan tercatat dengan nilai residu Rp 0 di sistem.</span>
          </p>
        </div>
      @endif
    @endif
  </div>

  <div class="p-6 md:p-8 bg-white dark:bg-[#140b0b]">
    <div class="flex items-center gap-3 border-b border-gray-200 dark:border-rose-900/30 pb-4 mb-6">
      <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
          </path>
        </svg>
      </div>
      <h3 class="text-base font-extrabold text-gray-900 dark:text-rose-50 uppercase tracking-widest">
        Status Supervisi
      </h3>
    </div>

    @if(!$record->is_kena_penyusutan)
      <div
        class="flex flex-col items-center justify-center py-8 px-4 text-center border border-dashed border-gray-300 dark:border-rose-900/30 rounded-2xl bg-gray-50 dark:bg-[#160c0c]">
        <span
          class="inline-flex items-center gap-2 px-4 py-2 text-xs font-extrabold uppercase tracking-widest text-gray-500 bg-gray-200 rounded-lg dark:bg-black/50 dark:text-gray-400 mb-3 border border-gray-300 dark:border-gray-700 shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
          </svg> Tidak Wajib Supervisi
        </span>
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 max-w-lg">
          Aset ini termasuk dalam inventaris minor (Ekstrakomptabel), sistem tidak mewajibkan pelacakan jadwal supervisi
          berkala.
        </p>
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
          class="p-5 rounded-2xl border border-gray-200 dark:border-rose-900/30 bg-white dark:bg-[#1a0e0e] shadow-sm hover:shadow-md transition-shadow">
          <p class="text-[10px] font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest mb-4">Inspeksi
            Terakhir</p>

          @if($record->tanggal_supervisi_aktif)
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 border border-emerald-200 dark:border-emerald-800/50 shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <div>
                <p class="font-extrabold text-xl text-gray-900 dark:text-white leading-tight">
                  {{ \Carbon\Carbon::parse($record->tanggal_supervisi_aktif)->translatedFormat('d M Y') }}
                </p>
                <p class="text-[11px] text-emerald-600 dark:text-emerald-500 font-bold mt-1 uppercase tracking-wider">
                  Terverifikasi</p>
              </div>
            </div>
          @else
            <div class="flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0 border border-rose-200 dark:border-rose-800/50 shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-extrabold text-xl text-rose-600 dark:text-rose-500 leading-tight">
                  Belum Supervisi
                </p>
                <p
                  class="text-[11px] text-rose-500 dark:text-rose-400 font-bold mt-1 uppercase tracking-wider animate-pulse">
                  Butuh Inspeksi Segera</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    @endif

    <div
      class="p-6 md:p-8 bg-gray-50/80 dark:bg-[#120a0a] border-t border-gray-200 dark:border-rose-900/30 shadow-[inset_0_4px_6px_rgba(0,0,0,0.02)]">

      @php
        $riwayatSupervisi = \App\Models\Supervisi::where('perangkat_id', $record->id)
          ->orderBy('tanggal', 'desc')
          ->get();
      @endphp

      <div class="flex items-center justify-between border-b border-gray-200 dark:border-rose-900/30 pb-4 mb-6">
        <div class="flex items-center gap-3">
          <div
            class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
              </path>
            </svg>
          </div>
          <h3 class="text-base font-extrabold text-gray-900 dark:text-rose-50 uppercase tracking-widest">
            Riwayat Supervisi
          </h3>
        </div>
        @if($riwayatSupervisi->isNotEmpty())
          <span
            class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest bg-gray-100 dark:bg-black/20 px-2 py-1 rounded">
            Scroll untuk melihat lebih banyak
          </span>
        @endif
      </div>

      @if($riwayatSupervisi->isEmpty())
        <div
          class="flex flex-col items-center justify-center py-10 px-4 text-center border border-dashed border-gray-300 dark:border-rose-900/30 rounded-2xl bg-white dark:bg-[#160c0c]">
          <div class="p-3 bg-gray-100 dark:bg-black/40 rounded-full mb-3">
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <p class="text-sm font-bold text-gray-900 dark:text-gray-300">Belum Ada Riwayat</p>
          <p class="text-xs font-medium text-gray-500 mt-1">Perangkat ini belum pernah disupervisi.</p>
        </div>
      @else
        <div class="max-h-[350px] overflow-y-auto pr-4 custom-scrollbar relative">
          <div
            class="relative pl-6 md:pl-8 border-l-2 border-rose-100 dark:border-rose-900/40 space-y-6 ml-3 md:ml-4 mb-4 mt-2">
            @foreach($riwayatSupervisi as $riwayat)
              <div class="relative group">
                <div
                  class="absolute -left-[2.1rem] md:-left-[2.6rem] top-1.5 w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950/80 border-[3px] border-gray-50 dark:border-[#120a0a] flex items-center justify-center shadow-sm z-10 group-hover:scale-110 transition-transform">
                  <span
                    class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 {{ $loop->first ? 'animate-pulse' : '' }}"></span>
                </div>

                <div
                  class="bg-white dark:bg-[#1a0e0e] border border-gray-100 dark:border-rose-900/30 rounded-2xl p-4 md:p-5 shadow-sm hover:shadow-md transition-shadow group-hover:border-rose-200 dark:group-hover:border-rose-800/50">
                  <div
                    class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3 pb-3 border-b border-gray-50 dark:border-rose-900/20">
                    <div class="flex items-center gap-2">
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-gray-50 dark:bg-black/50 border border-gray-200 dark:border-gray-800 text-[11px] font-extrabold text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)]">
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                          </path>
                        </svg>
                        {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('l, d F Y') }}
                      </span>
                      @if($loop->first)
                        <span
                          class="px-2 py-1 rounded bg-emerald-100/50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 text-[9px] font-bold uppercase tracking-wider border border-emerald-200 dark:border-emerald-800/50">Terbaru</span>
                      @endif
                    </div>
                    <div
                      class="flex items-center gap-2 text-[10px] font-bold text-gray-500 dark:text-rose-200/50 uppercase tracking-widest">
                      <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      Oleh: <span
                        class="text-gray-700 dark:text-rose-100">{{ $riwayat->user->name ?? 'User Sistem' }}</span>
                    </div>
                  </div>
                  <div class="pl-1">
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest mb-1.5">Catatan /
                      Keterangan</p>
                    <div class="text-sm text-gray-700 dark:text-gray-300 font-medium leading-relaxed italic">
                      {{ $riwayat->keterangan ?? 'Tidak ada catatan tambahan.' }}
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>

  <div x-data="{ activeTab: 'mutasi', isImageModalOpen: false, modalImageUrl: '' }" class="p-6 md:p-8 bg-white dark:bg-[#140b0b] border-t border-gray-200 dark:border-rose-900/30">
    
    <div class="flex flex-wrap items-center gap-2 mb-8 p-1.5 bg-gray-100/80 dark:bg-black/60 rounded-2xl w-fit border border-gray-300 dark:border-rose-900/40 shadow-sm">
        <button @click="activeTab = 'mutasi'" :class="activeTab === 'mutasi' ? 'bg-rose-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-rose-50 dark:hover:bg-rose-900/30'" class="px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            Riwayat Mutasi
        </button>
        <button @click="activeTab = 'pinjam'" :class="activeTab === 'pinjam' ? 'bg-rose-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-rose-50 dark:hover:bg-rose-900/30'" class="px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Riwayat Peminjaman
        </button>
        <button @click="activeTab = 'tarik'" :class="activeTab === 'tarik' ? 'bg-rose-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-rose-50 dark:hover:bg-rose-900/30'" class="px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            Riwayat Penarikan
        </button>
        <button @click="activeTab = 'maintenance'" :class="activeTab === 'maintenance' ? 'bg-rose-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-rose-50 dark:hover:bg-rose-900/30'" class="px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Riwayat Maintenance
        </button>
    </div>

    <div x-show="activeTab === 'mutasi'" x-transition:enter="transition ease-out duration-300" style="display: none;">
        @php
            $mutasis = \App\Models\Mutasi::with(['lokasiAsal', 'lokasiMutasi'])->where('perangkat_id', $record->id)->orderBy('tanggal_mutasi', 'desc')->get();
        @endphp
        
        @if($mutasis->isEmpty())
            <p class="text-center py-10 text-xs text-gray-500 font-bold uppercase tracking-widest">Belum ada riwayat mutasi.</p>
        @else
            <div class="max-h-[350px] overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @foreach($mutasis as $mutasi)
                    @php $alasanMutasi = is_array($mutasi->alasan_mutasi) ? implode(', ', $mutasi->alasan_mutasi) : $mutasi->alasan_mutasi; @endphp
                    <div class="group relative p-5 rounded-2xl border border-gray-200 dark:border-rose-900/40 bg-gray-50/80 dark:bg-[#180d0d] hover:border-rose-400 transition-all shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Dari</span>
                                    <span class="px-3 py-1.5 rounded-lg bg-white dark:bg-black/60 text-xs font-extrabold text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-700 shadow-sm">{{ $mutasi->lokasiAsal->nama_lokasi ?? 'Lokasi Awal' }}</span>
                                </div>
                                <div class="p-1.5 bg-rose-100 dark:bg-rose-900/40 rounded-full text-rose-600 shadow-inner">
                                    <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Ke Tujuan</span>
                                    @php $namaTujuan = \App\Models\Lokasi::find($mutasi->lokasi_mutasi_id)?->nama_lokasi ?? 'N/A'; @endphp
                                    <span class="px-3 py-1.5 rounded-lg bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold shadow-md">{{ $namaTujuan }}</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6 text-right bg-white dark:bg-black/40 p-3 rounded-xl border border-gray-200 dark:border-rose-900/20 shadow-sm">
                                <div>
                                    <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">Tgl Mutasi</p>
                                    <p class="text-xs font-extrabold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1">Diterima</p>
                                    <p class="text-xs font-extrabold {{ $mutasi->tanggal_diterima ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $mutasi->tanggal_diterima ? \Carbon\Carbon::parse($mutasi->tanggal_diterima)->format('d/m/Y') : 'Menunggu' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 border-t border-gray-200 dark:border-rose-900/30 pt-3">
                            <span class="font-black text-gray-600 dark:text-gray-300 uppercase text-[10px] block mb-1">Alasan Mutasi:</span>
                            <p class="text-xs text-gray-800 dark:text-gray-200 font-medium italic">"{{ $alasanMutasi ?? 'Tidak ada keterangan tertulis.' }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div x-show="activeTab === 'pinjam'" x-transition:enter="transition ease-out duration-300" style="display: none;">
        @php
            $peminjamans = \App\Models\Peminjaman::where('perangkat_id', $record->id)->orderBy('tanggal_mulai', 'desc')->get();
        @endphp
        
        @if($peminjamans->isEmpty())
            <p class="text-center py-10 text-xs text-gray-500 font-bold uppercase tracking-widest">Aset belum pernah dipinjam.</p>
        @else
            <div class="max-h-[350px] overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @foreach($peminjamans as $pinjam)
                    @php $alasanPinjam = is_array($pinjam->alasan_pinjam) ? implode(', ', $pinjam->alasan_pinjam) : $pinjam->alasan_pinjam; @endphp
                    <div class="p-5 rounded-2xl border border-gray-200 dark:border-rose-900/40 bg-gray-50/80 dark:bg-[#180d0d] hover:border-rose-400 transition-all shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                @php $namaPeminjam = $pinjam->pihak_kedua_nama ?? 'Pihak Luar'; @endphp
                                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-rose-100 to-rose-200 dark:from-rose-900/60 dark:to-red-900/40 flex items-center justify-center text-rose-800 dark:text-rose-300 font-black text-lg shadow-inner border border-rose-300 dark:border-rose-700">{{ strtoupper(substr($namaPeminjam, 0, 1)) }}</div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $namaPeminjam }}</p>
                                    @php $namaApprover = \App\Models\User::find($pinjam->approved_by_user_id)?->name ?? 'System/Pending'; @endphp
                                    <p class="text-[10px] text-gray-600 dark:text-gray-400 uppercase tracking-wider font-extrabold mt-0.5">Approved by: <span class="text-rose-600 dark:text-rose-400">{{ $namaApprover }}</span></p>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm {{ strtolower($pinjam->status) == 'kembali' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300 animate-pulse' }}">{{ $pinjam->status ?? 'Aktif' }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-6 bg-white dark:bg-black/40 p-4 rounded-xl mb-4 border border-gray-200 dark:border-rose-900/20 shadow-sm">
                            <div>
                                <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1 flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Mulai Pinjam</p>
                                <p class="text-xs font-extrabold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($pinjam->tanggal_mulai)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase mb-1 flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Target Kembali</p>
                                <p class="text-xs font-extrabold text-gray-900 dark:text-white">{{ $pinjam->tanggal_selesai ? \Carbon\Carbon::parse($pinjam->tanggal_selesai)->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        <div class="px-2 pt-2 border-t border-gray-200 dark:border-rose-900/30">
                            <p class="text-[10px] font-black text-gray-600 dark:text-gray-300 uppercase mb-1.5">Alasan Meminjam:</p>
                            <p class="text-xs text-gray-800 dark:text-gray-200 font-semibold italic">"{{ $alasanPinjam ?? '-' }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div x-show="activeTab === 'tarik'" x-transition:enter="transition ease-out duration-300" style="display: none;">
        @php
            $penarikans = \App\Models\PenarikanAlat::where('perangkat_id', $record->id)->orderBy('tanggal_penarikan', 'desc')->get();
        @endphp
        
        @if($penarikans->isEmpty())
            <p class="text-center py-10 text-xs text-gray-500 font-bold uppercase tracking-widest">Tidak ada riwayat penarikan.</p>
        @else
            <div class="max-h-[350px] overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @foreach($penarikans as $tarik)
                    @php
                        $alasanTarik = is_array($tarik->alasan_penarikan) ? implode(', ', $tarik->alasan_penarikan) : $tarik->alasan_penarikan;
                        $catatanTarik = is_array($tarik->alasan_lainnya) ? implode(', ', $tarik->alasan_lainnya) : $tarik->alasan_lainnya;
                        $rawTipe = $tarik->tindak_lanjut_tipe;
                        $tipeArr = is_array($rawTipe) ? $rawTipe : ($rawTipe ? [$rawTipe] : []);
                        $tipeHtml = '';
                        foreach($tipeArr as $t) { $tipeHtml .= "<span class='uppercase bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-2 py-1 rounded-md text-[10px] font-black mr-1.5 border border-gray-300 dark:border-gray-600'>" . htmlspecialchars((string)$t) . "</span>"; }
                        $detailTindakLanjut = is_array($tarik->tindak_lanjut_detail) ? implode(', ', $tarik->tindak_lanjut_detail) : $tarik->tindak_lanjut_detail;
                    @endphp
                    <div class="relative overflow-hidden p-6 rounded-2xl border border-red-300 dark:border-red-900/50 bg-gradient-to-br from-red-50 to-white dark:from-[#1f0d0d] dark:to-[#140808] shadow-md hover:shadow-lg transition-shadow">
                        <div class="absolute top-0 right-0 p-4 opacity-[0.05] dark:opacity-[0.08] pointer-events-none">
                            <svg class="w-32 h-32 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <div class="flex items-center gap-4 mb-6 relative z-10">
                            <div class="p-3 bg-red-100 dark:bg-red-900/60 rounded-xl text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/50 shadow-inner"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                            <div>
                                <h4 class="text-base font-black text-red-800 dark:text-red-400 uppercase tracking-tight">Aset Resmi Ditarik</h4>
                                <p class="text-[11px] font-bold text-red-900/70 dark:text-red-300/70 mt-1">Dieksekusi pada: <span class="text-red-700 dark:text-red-400">{{ \Carbon\Carbon::parse($tarik->tanggal_penarikan)->translatedFormat('d F Y') }}</span></p>
                            </div>
                        </div>
                        <div class="space-y-5 bg-white/70 dark:bg-black/40 p-5 rounded-xl border border-red-200/60 dark:border-red-900/30 relative z-10 shadow-sm">
                            <div>
                                <p class="text-[10px] font-black text-red-900/60 dark:text-red-400/60 uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Alasan Penarikan Utama</p>
                                <p class="text-sm font-extrabold text-gray-900 dark:text-gray-100 ml-3.5">{{ $alasanTarik ?? '-' }}</p>
                                @if($catatanTarik)
                                    <p class="text-xs text-gray-700 dark:text-gray-300 italic mt-2 ml-3.5 border-l-[3px] border-red-300 dark:border-red-800 pl-3 font-medium">Catatan: "{{ $catatanTarik }}"</p>
                                @endif
                            </div>
                            <div class="pt-4 border-t border-red-200 dark:border-red-900/40">
                                <p class="text-[10px] font-black text-red-900/60 dark:text-red-400/60 uppercase tracking-widest mb-2.5 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Status Tindak Lanjut</p>
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 ml-3.5 leading-relaxed">{!! $tipeHtml !!} <span class="mt-1 block">{{ $detailTindakLanjut ?? 'Belum ada detail tindak lanjut dari manajemen.' }}</span></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div x-show="activeTab === 'maintenance'" x-transition:enter="transition ease-out duration-300" style="display: none;">
        @php
            $maintenances = \App\Models\RiwayatMaintenance::with(['user', 'maintenanceTypes', 'komponenDetails.komponen'])
                ->where('perangkat_id', $record->id)
                ->orderBy('tanggal_maintenance', 'desc')
                ->get();
        @endphp

        @if($maintenances->isEmpty())
            <p class="text-center py-10 text-xs text-gray-500 font-bold uppercase tracking-widest">Belum ada riwayat perbaikan/perawatan.</p>
        @else
            <style> [x-cloak] { display: none !important; } </style>
            
            <div class="max-h-[450px] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @foreach($maintenances as $mnt)
                    <div x-data="{ expanded: false }" class="bg-white dark:bg-[#160c0c] border border-gray-300 dark:border-rose-900/50 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        
                        <div @click="expanded = !expanded" class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 p-4 md:px-5 cursor-pointer bg-gray-50/80 dark:bg-black/20 hover:bg-gray-100 dark:hover:bg-black/40 transition-colors group">
                            
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-indigo-900/40 text-indigo-400 rounded-xl shadow-inner border border-indigo-800/50 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-black text-base text-gray-900 dark:text-white">{{ $mnt->tanggal_maintenance ? \Carbon\Carbon::parse($mnt->tanggal_maintenance)->translatedFormat('l, d F Y') : '-' }}</p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mt-1">Pemilik/Pengguna: <span class="text-rose-600 dark:text-rose-400">{{ $mnt->nama_pemilik ?? '-' }}</span></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end mt-2 md:mt-0">
                                @php
                                    $statusMnt = strtolower(trim($mnt->status_akhir));
                                    $statusClass = 'bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700'; 
                                    
                                    if($statusMnt === 'berfungsi') {
                                        $statusClass = 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-500 dark:border-emerald-800/50';
                                    } elseif($statusMnt === 'berfungsi_sebagian') {
                                        $statusClass = 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950/50 dark:text-amber-500 dark:border-amber-800/50 animate-pulse';
                                    } elseif($statusMnt === 'tidak_berfungsi') {
                                        $statusClass = 'bg-red-100 text-red-800 border-red-300 dark:bg-red-950/50 dark:text-red-400 dark:border-red-900/50';
                                    }
                                    
                                    $displayStatus = str_replace('_', ' ', $mnt->status_akhir ?? 'N/A');
                                @endphp
                                <span class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusClass }}">
                                    {{ $displayStatus }}
                                </span>

                                <div class="text-gray-400 dark:text-gray-500 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div x-show="expanded" x-collapse x-cloak>
                            <div class="p-5 md:p-6 grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-200 dark:border-rose-900/30">
                                
                                <div class="md:col-span-2 space-y-5">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Jenis Maintenance</p>
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($mnt->maintenanceTypes as $type)
                                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-md border border-gray-300 dark:border-gray-600 shadow-sm">
                                                    {{ $type->nama ?? $type->name ?? 'Tipe Umum' }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-500 italic font-medium">Tidak diklasifikasikan</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tindakan Komponen</p>
                                        <div class="bg-indigo-50/80 dark:bg-indigo-950/30 p-4 rounded-xl border border-indigo-200 dark:border-indigo-900/50 shadow-sm">
                                            <p class="text-xs font-bold text-indigo-900 dark:text-indigo-200 leading-relaxed">
                                                @if($mnt->komponen_summary)
                                                    {!! nl2br(e($mnt->komponen_summary)) !!}
                                                @else
                                                    <span class="italic text-indigo-500/70">Tidak ada aksi spesifik pada komponen.</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Deskripsi & Pekerjaan Dilakukan</p>
                                        <p class="text-xs text-gray-900 dark:text-white font-bold bg-gray-50 dark:bg-black/40 p-4 rounded-xl border border-gray-200 dark:border-rose-900/20 shadow-inner leading-relaxed">
                                            {{ $mnt->deskripsi ?? 'Tidak ada deskripsi rinci.' }}
                                        </p>
                                    </div>

                                    @if($mnt->catatan)
                                    <div class="pt-2 border-t border-gray-200 dark:border-rose-900/30">
                                        <p class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-2 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Catatan Teknisi</p>
                                        <p class="text-xs text-gray-700 dark:text-gray-300 font-bold italic border-l-[3px] border-amber-400 dark:border-amber-600 pl-3">"{{ $mnt->catatan }}"</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-200 dark:border-rose-900/30 pt-5 md:pt-0 md:pl-5">
                                    <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Foto Dokumentasi</p>
                                    
                                    @php $fotos = is_array($mnt->foto) ? $mnt->foto : []; @endphp

                                    @if(count($fotos) > 0)
                                        <div class="flex flex-wrap gap-3">
                                            @foreach($fotos as $ft)
                                                <div @click="isImageModalOpen = true; modalImageUrl = '{{ \Illuminate\Support\Facades\Storage::url($ft) }}'" class="relative w-16 h-16 rounded-xl overflow-hidden border-2 border-gray-300 dark:border-rose-900/50 shadow-sm cursor-pointer hover:ring-2 hover:ring-rose-500 hover:ring-offset-2 dark:hover:ring-offset-[#160c0c] transition-all group/img">
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($ft) }}" alt="Dokumentasi" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-300">
                                                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center p-5 bg-gray-50/80 dark:bg-black/30 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-800">
                                            <svg class="w-7 h-7 text-gray-400 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Tidak ada foto</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="px-6 py-3 bg-gray-100/50 dark:bg-black/60 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-right border-t border-gray-200 dark:border-rose-900/30">
                                Ditambahkan Oleh: <span class="text-rose-600 dark:text-rose-400 ml-1">{{ $mnt->user->name ?? 'Admin Sistem' }}</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div x-show="isImageModalOpen" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-md" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div @click.away="isImageModalOpen = false" class="relative w-full max-w-5xl max-h-screen p-4 flex flex-col items-center justify-center">
            
            <button @click="isImageModalOpen = false" class="absolute top-4 right-4 md:top-8 md:right-8 p-2.5 bg-white/10 hover:bg-rose-600 text-white rounded-full transition-colors duration-300 shadow-lg border border-white/20 backdrop-blur-lg z-50 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <img :src="modalImageUrl" alt="Foto Detail" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-[0_0_50px_rgba(0,0,0,0.5)] border border-white/10 transition-transform" @click.stop>
            
            <p class="text-white/60 text-xs font-medium mt-4 tracking-widest uppercase">Tekan tombol [X] atau klik di luar gambar untuk menutup</p>
        </div>
    </div>
    
  </div>

  @if($record->keterangan)
    <div class="px-6 md:px-8 pb-8 bg-white dark:bg-[#140b0b]">
      <div
        class="bg-amber-50/80 dark:bg-amber-900/10 border border-amber-200/80 dark:border-amber-700/30 rounded-xl p-5 shadow-sm">
        <h4
          class="text-xs font-extrabold text-amber-800 dark:text-amber-500 uppercase tracking-widest mb-2.5 flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Catatan Tambahan
        </h4>
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 italic leading-relaxed">
          "{{ $record->keterangan }}"
        </p>
      </div>
    </div>
  @endif

  <div
    class="bg-gray-50/80 dark:bg-black/40 px-6 py-4 border-t border-gray-200 dark:border-rose-900/30 flex justify-between items-center text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
    <div class="flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
      </svg>
      Dibuat: <span class="text-gray-600 dark:text-gray-300">{{ $record->created_at?->format('d M Y, H:i') }}</span>
    </div>
    <div class="flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
        </path>
      </svg>
      Update: <span class="text-gray-600 dark:text-gray-300">{{ $record->updated_at?->format('d M Y, H:i') }}</span>
    </div>
  </div>
</div>