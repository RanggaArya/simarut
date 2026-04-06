<x-filament-panels::page>
    
    {{-- CSS MAGIC UNTUK TEMA RED WINE VIP & FREEZE --}}
    <style>
        /* WADAH SCROLL PREMIUM */
        .wadah-resume-vip {
            max-height: 65vh;
            overflow: auto;
            position: relative;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(153, 27, 27, 0.1);
        }
        
        .wadah-resume-vip::-webkit-scrollbar { width: 8px; height: 8px; }
        .wadah-resume-vip::-webkit-scrollbar-track { background: rgba(0,0,0,0.02); }
        .wadah-resume-vip::-webkit-scrollbar-thumb { background: rgba(153, 27, 27, 0.4); border-radius: 4px; }
        .wadah-resume-vip::-webkit-scrollbar-thumb:hover { background: rgba(153, 27, 27, 0.8); }
        .dark .wadah-resume-vip::-webkit-scrollbar-thumb { background: rgba(153, 27, 27, 0.6); }

        /* RESET TABEL */
        table.tabel-resume-vip {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100%;
        }

        /* =======================================
           HEADER (STICKY & RED WINE THEME)
           ======================================= */
        table.tabel-resume-vip thead th {
            background: linear-gradient(135deg, #991b1b 0%, #450a0a 100%) !important;
            color: #ffffff !important;
            border-bottom: 2px solid #000000 !important;
            border-right: 1px solid rgba(255,255,255,0.1) !important;
            backdrop-filter: blur(10px);
            transition: z-index 0.2s; /* Transisi halus saat popup buka/tutup */
        }
        .dark table.tabel-resume-vip thead th {
            background: linear-gradient(135deg, #450a0a 0%, #000000 100%) !important;
            border-bottom: 2px solid #7f1d1d !important;
        }

        /* Timpa warna sub-header */
        table.tabel-resume-vip thead tr:nth-child(2) th,
        table.tabel-resume-vip thead tr:nth-child(3) th {
            background: #7f1d1d !important;
            color: #ffffff !important;
        }
        .dark table.tabel-resume-vip thead tr:nth-child(2) th,
        .dark table.tabel-resume-vip thead tr:nth-child(3) th {
            background: #2a0404 !important;
        }

        /* =======================================
           BODY (SOLID BACKGROUND ANTI-TEMBUS)
           ======================================= */
        table.tabel-resume-vip tbody td {
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(153, 27, 27, 0.05);
            transition: all 0.2s;
        }
        table.tabel-resume-vip tbody tr:nth-child(even) td { background-color: #fef8f8 !important; }
        table.tabel-resume-vip tbody tr:hover td { background-color: #fee2e2 !important; }

        .dark table.tabel-resume-vip tbody td { background-color: #120404 !important; border-bottom: 1px solid rgba(153,27,27,0.1); }
        .dark table.tabel-resume-vip tbody tr:nth-child(even) td { background-color: #1a0505 !important; }
        .dark table.tabel-resume-vip tbody tr:hover td { background-color: #380b0b !important; color: #fff; }

        /* =======================================
           FOOTER (GRAND TOTAL STICKY BOTTOM)
           ======================================= */
        table.tabel-resume-vip tfoot td {
            position: sticky !important;
            bottom: 0 !important;
            z-index: 30 !important;
            background: #f1f5f9 !important;
            border-top: 3px solid #991b1b !important;
            font-weight: 800;
        }
        .dark table.tabel-resume-vip tfoot td {
            background: #000000 !important;
            border-top: 3px solid #dc2626 !important;
        }

        /* =======================================
           FREEZE 2 KOLOM KIRI (HANYA PC/DESKTOP)
           ======================================= */
        .is-desktop table.tabel-resume-vip tbody tr td:nth-child(1) { position: sticky !important; left: 0 !important; z-index: 10 !important; }
        .is-desktop table.tabel-resume-vip tbody tr td:nth-child(2) { 
            position: sticky !important; 
            left: var(--w1, 50px) !important; 
            z-index: 10 !important;
            box-shadow: 4px 0 10px -2px rgba(0,0,0,0.1); 
        }
        .dark.is-desktop table.tabel-resume-vip tbody tr td:nth-child(2) { box-shadow: 4px 0 10px -2px rgba(0,0,0,0.6); }

        .is-desktop table.tabel-resume-vip tfoot tr td:nth-child(1) { position: sticky !important; left: 0 !important; z-index: 40 !important; }
        .is-desktop table.tabel-resume-vip tfoot tr td:nth-child(2) { 
            position: sticky !important; 
            left: var(--w1, 50px) !important; 
            z-index: 40 !important;
            box-shadow: 4px 0 10px -2px rgba(0,0,0,0.1); 
        }

        /* =======================================
           CLASS SAKTI: MENGANGKAT POPUP KE ATAS
           ======================================= */
        th.popup-terbuka {
            z-index: 9999 !important;
        }

        /* Drag to Scroll */
        body.is-dragging { user-select: none !important; cursor: grabbing !important; }
        body.is-dragging .wadah-resume-vip { pointer-events: none !important; }
    </style>

    <div class="wadah-resume-vip" id="resumeWrapper">
        <table class="tabel-resume-vip text-sm text-left text-gray-700 dark:text-gray-200" id="resumeTable">
            <thead class="text-xs uppercase">
                <tr>
                    {{-- KOLOM NO --}}
                    <th rowspan="3" class="px-4 py-2 text-center align-middle font-bold text-lg w-12">No</th>

                    {{-- KOLOM NAMA ALAT (DIPINDAHKAN X-DATA KE SINI) --}}
                    <th rowspan="3" x-data="{ open: false }" :class="open ? 'popup-terbuka' : ''" class="px-4 py-2 text-center align-middle font-bold text-lg w-1/4 relative group">
                        <div class="flex items-center justify-between px-2">
                            <span class="flex-grow text-center">
                                Nama Alat
                                @if(!empty($filter_nama))
                                    <span class="text-[10px] font-normal block normal-case text-gray-300">({{ count($filter_nama) }} dipilih)</span>
                                @endif
                            </span>

                            <button @click="open = !open" class="p-1 ml-2 rounded hover:bg-white/20 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 {{ !empty($filter_nama) ? 'text-yellow-300' : 'text-gray-300' }}">
                                    <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 01.628.74v2.288a2.25 2.25 0 01-.659 1.59l-4.682 4.683a2.25 2.25 0 00-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 018 18.25v-5.757a2.25 2.25 0 00-.659-1.591L2.659 6.22A2.25 2.25 0 012 4.629V2.34a.75.75 0 01.628-.74z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            {{-- POPUP FILTER --}}
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute top-full right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-md shadow-2xl ring-1 ring-black ring-opacity-5 z-50 text-left flex flex-col max-h-80" style="display:none;">
                                <div class="p-2 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-t-md flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Filter Item</span>
                                    @if(!empty($filter_nama))
                                        <button wire:click="$set('data.filter_nama', [])" class="text-xs text-red-500 hover:text-red-700 hover:underline">Clear</button>
                                    @endif
                                </div>
                                <div class="overflow-y-auto p-2 space-y-1" style="max-height: 250px;">
                                    @if(count($list_nama_alat) > 0)
                                        @foreach($list_nama_alat as $nama)
                                            <label class="flex items-center space-x-2 px-2 py-1 hover:bg-red-50 dark:hover:bg-red-900/30 rounded cursor-pointer">
                                                <input type="checkbox" value="{{ $nama }}" wire:model.live="data.filter_nama" class="rounded border-gray-300 text-red-700 focus:ring-red-500 dark:bg-gray-700">
                                                <span class="text-sm text-gray-700 dark:text-gray-200 truncate" title="{{ $nama }}">{{ $nama }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <div class="text-center text-xs text-gray-400 py-2">Data Kosong</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </th>

                    {{-- HEADER KONDISI ALAT (DIPINDAHKAN X-DATA KE SINI) --}}
                    <th @if($filter_kondisi === 'all') colspan="4" @elseif($filter_kondisi === 'baik' || $filter_kondisi === 'rusak') colspan="2" @endif x-data="{ open: false }" :class="open ? 'popup-terbuka' : ''" class="px-4 py-2 text-center font-bold text-lg relative group">
                        <div class="flex items-center justify-center gap-2">
                            <span>Kondisi Alat @if($filter_kondisi !== 'all') <span class="text-xs font-normal">({{ ucfirst($filter_kondisi) }})</span> @endif</span>
                            <button @click="open = !open" class="p-1 rounded hover:bg-white/20 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 {{ $filter_kondisi !== 'all' ? 'text-yellow-300' : 'text-gray-300' }}">
                                    <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 01.628.74v2.288a2.25 2.25 0 01-.659 1.59l-4.682 4.683a2.25 2.25 0 00-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 018 18.25v-5.757a2.25 2.25 0 00-.659-1.591L2.659 6.22A2.25 2.25 0 012 4.629V2.34a.75.75 0 01.628-.74z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute top-full right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-2xl ring-1 ring-black ring-opacity-5 z-50 text-left" style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b dark:border-gray-700">Filter Status</div>
                                    <button wire:click="$set('data.filter_kondisi', 'all'); open = false" class="block w-full px-4 py-2 text-sm text-left hover:bg-red-50 dark:hover:bg-red-900/30 {{ $filter_kondisi === 'all' ? 'font-bold text-red-700' : 'text-gray-700 dark:text-gray-200' }}">All</button>
                                    <button wire:click="$set('data.filter_kondisi', 'baik'); open = false" class="block w-full px-4 py-2 text-sm text-left hover:bg-red-50 dark:hover:bg-red-900/30 {{ $filter_kondisi === 'baik' ? 'font-bold text-green-600' : 'text-gray-700 dark:text-gray-200' }}">Baik</button>
                                    <button wire:click="$set('data.filter_kondisi', 'rusak'); open = false" class="block w-full px-4 py-2 text-sm text-left hover:bg-red-50 dark:hover:bg-red-900/30 {{ $filter_kondisi === 'rusak' ? 'font-bold text-red-600' : 'text-gray-700 dark:text-gray-200' }}">Rusak</button>
                                </div>
                            </div>
                        </div>
                    </th>

                    <th colspan="2" rowspan="2" class="px-4 py-2 text-center align-middle font-bold text-lg">Grand Total</th>
                </tr>

                {{-- SUB HEADERS 1 --}}
                <tr>
                    @if($filter_kondisi === 'all' || $filter_kondisi === 'baik')
                        <th colspan="2" class="px-2 py-1 text-center font-semibold text-green-200">Baik</th>
                    @endif
                    @if($filter_kondisi === 'all' || $filter_kondisi === 'rusak')
                        <th colspan="2" class="px-2 py-1 text-center font-semibold text-red-300">Rusak</th>
                    @endif
                </tr>

                {{-- SUB HEADERS 2 --}}
                <tr>
                    @if($filter_kondisi === 'all' || $filter_kondisi === 'baik')
                        <th class="px-4 py-2 text-right">Jumlah</th>
                        <th class="px-4 py-2 text-right">Harga Beli</th>
                    @endif
                    @if($filter_kondisi === 'all' || $filter_kondisi === 'rusak')
                        <th class="px-4 py-2 text-right">Jumlah</th>
                        <th class="px-4 py-2 text-right">Harga Beli</th>
                    @endif
                    <th class="px-4 py-2 text-right">Jumlah</th>
                    <th class="px-4 py-2 text-right">Harga Beli</th>
                </tr>
            </thead>

            {{-- BODY TABLE --}}
            <tbody>
                @forelse($resume_data as $row)
                    <tr>
                        <td class="px-4 py-2 text-center font-medium">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 font-medium border-r border-gray-200 dark:border-gray-700">{{ $row['nama_jenis'] ?? '-' }}</td>

                        @if($filter_kondisi === 'all' || $filter_kondisi === 'baik')
                            <td class="px-4 py-2 text-right text-green-600 dark:text-green-400 font-semibold">{{ number_format($row['baik_qty'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right text-green-600 dark:text-green-400 border-r border-gray-200 dark:border-gray-700">Rp {{ number_format($row['baik_harga'], 0, ',', '.') }}</td>
                        @endif

                        @if($filter_kondisi === 'all' || $filter_kondisi === 'rusak')
                            <td class="px-4 py-2 text-right text-red-600 dark:text-red-400 font-semibold">{{ number_format($row['rusak_qty'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-right text-red-600 dark:text-red-400 border-r border-gray-200 dark:border-gray-700">Rp {{ number_format($row['rusak_harga'], 0, ',', '.') }}</td>
                        @endif

                        <td class="px-4 py-2 text-right font-bold text-gray-900 dark:text-white">{{ number_format($row['total_qty'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right font-bold text-gray-900 dark:text-white">Rp {{ number_format($row['total_harga'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 italic">Tidak ada data perangkat ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>

            {{-- FOOTER --}}
            <tfoot>
                <tr>
                    <td class="px-4 py-3"></td>
                    <td class="px-4 py-3 text-center uppercase border-r border-gray-300 dark:border-gray-700">Grand Total</td>

                    @if($filter_kondisi === 'all' || $filter_kondisi === 'baik')
                        <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">{{ number_format($grand_total['baik_qty'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600 dark:text-green-400 border-r border-gray-300 dark:border-gray-700">Rp {{ number_format($grand_total['baik_harga'], 0, ',', '.') }}</td>
                    @endif

                    @if($filter_kondisi === 'all' || $filter_kondisi === 'rusak')
                        <td class="px-4 py-3 text-right text-red-600 dark:text-red-400">{{ number_format($grand_total['rusak_qty'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-red-600 dark:text-red-400 border-r border-gray-300 dark:border-gray-700">Rp {{ number_format($grand_total['rusak_harga'], 0, ',', '.') }}</td>
                    @endif

                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white">{{ number_format($grand_total['total_qty'], 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right text-gray-900 dark:text-white">Rp {{ number_format($grand_total['total_harga'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- SCRIPT PINTAR UNTUK NESTED STICKY HEADER & RESPONSIVE FREEZE --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const wrapper = document.getElementById("resumeWrapper");
            const table = document.getElementById("resumeTable");

            function updateTableLayout() {
                const isDesktop = window.innerWidth >= 1024;
                
                if (isDesktop) {
                    wrapper.classList.add('is-desktop');
                    const tbody = table.querySelector('tbody');
                    if(tbody && tbody.children.length > 0) {
                        const col1 = tbody.children[0].children[0];
                        if(col1) table.style.setProperty('--w1', col1.offsetWidth + 'px');
                    }
                } else {
                    wrapper.classList.remove('is-desktop');
                }

                let currentTop = 0;
                table.querySelectorAll('thead tr').forEach((tr, rowIndex) => {
                    let ths = tr.querySelectorAll('th');
                    
                    ths.forEach((th, colIndex) => {
                        th.style.setProperty('position', 'sticky', 'important');
                        th.style.setProperty('top', currentTop + 'px', 'important');
                        
                        // Perbaikan Z-Index: Berikan nilai normal saja (tanpa !important)
                        // supaya class .popup-terbuka (z-index: 9999 !important) dari Alpine bisa menang.
                        if (isDesktop && rowIndex === 0 && (colIndex === 0 || colIndex === 1)) {
                            th.style.zIndex = '50';
                        } else {
                            th.style.zIndex = '20';
                        }
                    });

                    let thNormal = Array.from(ths).find(th => !th.hasAttribute('rowspan') || th.getAttribute('rowspan') === '1');
                    let rowHeight = thNormal ? thNormal.getBoundingClientRect().height : tr.getBoundingClientRect().height;
                    currentTop += Math.ceil(rowHeight);
                });
            }

            setTimeout(updateTableLayout, 300);
            window.addEventListener('resize', updateTableLayout);

            let isDown = false; let startX; let scrollLeft;
            
            wrapper.addEventListener("mousedown", (e) => {
                if (e.target.closest("button, input, .absolute")) return;
                isDown = true;
                document.body.classList.add("is-dragging");
                startX = e.pageX - wrapper.offsetLeft;
                scrollLeft = wrapper.scrollLeft;
            });
            
            document.addEventListener("mouseup", () => {
                isDown = false;
                document.body.classList.remove("is-dragging");
            });
            
            wrapper.addEventListener("mousemove", (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - wrapper.offsetLeft;
                const walk = (x - startX) * 1.5;
                wrapper.scrollLeft = scrollLeft - walk;
            });
            
            document.addEventListener("livewire:navigated", () => setTimeout(updateTableLayout, 300));
        });
    </script>
</x-filament-panels::page>