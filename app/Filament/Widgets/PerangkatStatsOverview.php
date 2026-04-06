<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Perangkat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class PerangkatStatsOverview extends BaseWidget
{
    protected ?string $heading = 'Dashboard Overview';
    
    // WAJIB STATIC untuk SIMARUT
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 12;

    // WAJIB NON-STATIC untuk SIMARUT
    protected ?string $pollingInterval = '15s'; 

    // WAJIB: Memaksa layout menjadi 4 kolom sejajar
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        // Cache selama 5 menit agar performa tetap kencang
        return Cache::remember('stats.perangkat.simarut.premium.v9', 300, function () {
            
            // Pengambilan Data
            $totalPerangkat   = Perangkat::count();
            $aktif            = Perangkat::whereHas('status', fn($q) => $q->where('nama_status', 'Aktif'))->count();
            $rusak            = Perangkat::whereHas('status', fn($q) => $q->where('nama_status', 'Rusak'))->count();
            $tidakDigunakan   = Perangkat::whereHas('status', fn($q) => $q->where('nama_status', 'Sudah tidak digunakan'))->count();

            return [
                // 1. TOTAL PERANGKAT (BIRU / INFO)
                Stat::make(new HtmlString('<span class="text-premium">Total Perangkat</span>'), number_format($totalPerangkat))
                    ->description('Inventaris Terdaftar')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->chart([10, 5, 12, 8, 15, 10, 20])
                    ->color('info') // Diubah jadi Biru
                    ->icon('heroicon-o-computer-desktop')
                    ->extraAttributes([
                        'class' => 'stat-premium stat-info',
                        'style' => 'background: rgba(14, 165, 233, 0.08); border-bottom: 4px solid #0ea5e9; backdrop-filter: blur(12px); border-radius: 16px; animation-delay: 0.1s;'
                    ]),

                // 2. AKTIF (HIJAU / SUCCESS)
                Stat::make(new HtmlString('<span class="text-premium">Perangkat Aktif</span>'), number_format($aktif))
                    ->description('Siap digunakan')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->chart([2, 5, 3, 7, 4, 8, 10])
                    ->color('success')
                    ->icon('heroicon-o-cpu-chip')
                    ->extraAttributes([
                        'class' => 'stat-premium stat-success',
                        'style' => 'background: rgba(16, 185, 129, 0.08); border-bottom: 4px solid #10b981; backdrop-filter: blur(12px); border-radius: 16px; animation-delay: 0.2s;'
                    ]),

                // 3. RUSAK (KUNING / WARNING)
                Stat::make(new HtmlString('<span class="text-premium">Perangkat Rusak</span>'), number_format($rusak))
                    ->description('Kondisi Rusak')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->chart([10, 8, 12, 5, 7, 3, 2])
                    ->color('warning')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->extraAttributes([
                        'class' => 'stat-premium stat-warning',
                        'style' => 'background: rgba(245, 158, 11, 0.08); border-bottom: 4px solid #f59e0b; backdrop-filter: blur(12px); border-radius: 16px; animation-delay: 0.3s;'
                    ]),

                // 4. TIDAK DIGUNAKAN (MERAH / DANGER) + INJEKSI CSS ANIMASI
                Stat::make(new HtmlString('<span class="text-premium">Perangkat Non-Aktif</span>'), number_format($tidakDigunakan))
                    ->description(new HtmlString('Unit Tidak Digunakan
                        <style>
                            /* ANIMASI MUNCUL BERGANTIAN (FADE UP) */
                            .stat-premium { 
                                opacity: 0;
                                animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                                position: relative;
                                overflow: hidden;
                                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease !important; 
                                cursor: pointer; 
                                border: 1px solid rgba(255, 255, 255, 0.1) !important; 
                            }
                            
                            @keyframes slideUpFade {
                                0% { opacity: 0; transform: translateY(40px); }
                                100% { opacity: 1; transform: translateY(0); }
                            }

                            /* EFEK KILAPAN KACA (SWEEPING SHINE) */
                            .stat-premium::before {
                                content: "";
                                position: absolute;
                                top: 0;
                                left: -150%;
                                width: 50%;
                                height: 100%;
                                background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0) 100%);
                                transform: skewX(-25deg);
                                animation: shineSweep 5s infinite;
                                pointer-events: none;
                                z-index: 1;
                            }

                            @keyframes shineSweep {
                                0% { left: -150%; }
                                20% { left: 200%; }
                                100% { left: 200%; }
                            }

                            /* Hover Saat Disorot (Melompat & Shadow Putih Terang) */
                            .stat-premium:hover { 
                                transform: translateY(-8px) scale(1.02) !important; 
                                border-color: rgba(255, 255, 255, 0.6) !important;
                                box-shadow: 0 15px 35px rgba(255, 255, 255, 0.25), 0 0 20px rgba(255, 255, 255, 0.1) !important; 
                                z-index: 10;
                            }
                            
                            /* IKON BERDENYUT & BERCAHAYA (BREATHING GLOW) */
                            .stat-premium svg {
                                animation: breatheGlow 2.5s infinite alternate ease-in-out;
                                position: relative;
                                z-index: 2;
                            }

                            /* Pewarnaan Glow Ikon Khusus */
                            .stat-info svg    { color: #0ea5e9 !important; filter: drop-shadow(0 0 5px rgba(14,165,233,0.6)); }
                            .stat-success svg { color: #10b981 !important; filter: drop-shadow(0 0 5px rgba(16,185,129,0.6)); }
                            .stat-warning svg { color: #f59e0b !important; filter: drop-shadow(0 0 5px rgba(245,158,11,0.6)); }
                            .stat-danger svg  { color: #f43f5e !important; filter: drop-shadow(0 0 5px rgba(244,63,94,0.6)); }

                            @keyframes breatheGlow {
                                0% { transform: scale(1); filter: drop-shadow(0 0 2px currentColor); }
                                100% { transform: scale(1.15); filter: drop-shadow(0 0 12px currentColor); }
                            }

                            /* Mode Gelap (Dark Mode) */
                            .dark .stat-premium { border: 1px solid rgba(255, 255, 255, 0.05) !important; }
                            .dark .stat-premium:hover { box-shadow: 0 15px 40px rgba(255, 255, 255, 0.25), 0 0 25px rgba(255, 255, 255, 0.15) !important; }
                            .dark .stat-info    { background: rgba(14, 165, 233, 0.15) !important; }
                            .dark .stat-success { background: rgba(16, 185, 129, 0.15) !important; }
                            .dark .stat-warning { background: rgba(245, 158, 11, 0.15) !important; }
                            .dark .stat-danger  { background: rgba(244, 63, 94, 0.15) !important; }
                            
                            .dark .stat-premium::before {
                                background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.08) 50%, rgba(255,255,255,0) 100%);
                            }
                            
                            .text-premium { font-weight: 800 !important; letter-spacing: 0.3px; }
                        </style>
                    '))
                    ->descriptionIcon('heroicon-m-minus-circle')
                    ->chart([2, 2, 3, 2, 4, 3, 5])
                    ->color('danger') // Diubah jadi Merah
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->extraAttributes([
                        'class' => 'stat-premium stat-danger',
                        'style' => 'background: rgba(244, 63, 94, 0.08); border-bottom: 4px solid #f43f5e; backdrop-filter: blur(12px); border-radius: 16px; animation-delay: 0.4s;'
                    ]),
            ];
        });
    }
}