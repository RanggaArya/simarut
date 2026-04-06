<?php

namespace App\Providers\Filament;

use Filament\View\PanelsRenderHook;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Auth\Login;
use App\Models\User;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(Login::class)
            ->topbar(false) 
            ->globalSearch(false)
            ->brandLogo(null)
            ->brandName('SIMARUT Dashboard')
            ->favicon(asset('img/RSU.png'))
            
            // =======================================================
            // TEMA WARNA BIRU TUA (NAVY)
            // =======================================================
            ->colors([
                'primary' => Color::hex('#1e3a8a'),
                'danger'  => Color::Red,
                'warning' => Color::Orange,
                'success' => Color::Emerald,
                'info'    => Color::Sky,
                'gray'    => Color::Slate,
            ])
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            
            // =======================================================
            // 1. RENDER HOOK: CSS UNTUK SCROLLING & VISUAL
            // =======================================================
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<style>
                    /* Hilangkan elemen sidebar ekstra */
                    aside .fi-sidebar-header, aside .fi-sidebar-footer { display: none !important; }

                    /* 1. BONGKAR PENGHALANG BAWAAN FILAMENT */
                    .fi-ta-content {
                        overflow: visible !important; /* WAJIB! Agar sticky tidak dibunuh */
                    }

                    /* 2. CLASS WADAH SCROLL UTAMA (Disuntikkan via JS) */
                    .wadah-scroll-mantap {
                        display: block !important;
                        max-height: 55vh !important; /* Batas tinggi agar bisa scroll vertikal */
                        overflow-y: auto !important;
                        overflow-x: auto !important;
                        position: relative !important;
                        width: 100% !important;
                    }

                    /* Custom Scrollbar Elegan */
                    .wadah-scroll-mantap::-webkit-scrollbar { width: 10px; height: 10px; }
                    .wadah-scroll-mantap::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
                    .wadah-scroll-mantap::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
                    .wadah-scroll-mantap::-webkit-scrollbar-thumb:hover { background: #1e3a8a; }
                    .dark .wadah-scroll-mantap::-webkit-scrollbar-track { background: #18181b; }
                    .dark .wadah-scroll-mantap::-webkit-scrollbar-thumb { background: #3f3f46; }

                    /* 3. RESET TABLE AGAR BEBAS BERGERAK */
                    table.fi-ta-table {
                        border-collapse: separate !important;
                        border-spacing: 0 !important;
                        width: 100% !important;
                    }
                    /* Lepaskan transform yang sering bikin sticky error */
                    .fi-ta-table, .fi-ta-table thead, .fi-ta-table tbody, .fi-ta-table tr {
                        position: static !important;
                        transform: none !important;
                        will-change: auto !important;
                    }

                    /* 4. STICKY HEADER UTAMA (ATAS) */
                    .fi-ta-table thead tr th {
                        position: sticky !important;
                        top: 0 !important;
                        z-index: 50 !important; /* Z-Index Tinggi */
                        background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%) !important;
                        border-bottom: 4px solid #172554 !important;
                        padding-top: 10px !important; padding-bottom: 10px !important;
                    }
                    .fi-ta-table thead tr th *, .fi-ta-table thead tr th button span, .fi-ta-table thead tr th svg {
                        color: #ffffff !important; opacity: 1 !important; font-weight: 600 !important;
                    }
                    .dark .fi-ta-table thead tr th {
                        background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%) !important;
                        border-bottom: 3px solid #020617 !important;
                    }

                    /* 5. BACKGROUND SEL & ZEBRA (Agar Freeze Tidak Transparan) */
                    .fi-ta-table tbody tr { transition: background-color 0.2s ease; background-color: #ffffff !important; }
                    .fi-ta-table tbody tr:nth-child(even) { background-color: #f8fafc !important; } 
                    .fi-ta-table tbody tr:hover { background-color: #eff6ff !important; } 
                    
                    .dark .fi-ta-table tbody tr { background-color: #18181b !important; color: #e2e8f0 !important; }
                    .dark .fi-ta-table tbody tr:nth-child(even) { background-color: #27272a !important; } 
                    .dark .fi-ta-table tbody tr:hover { background-color: #172554 !important; color: #ffffff !important; } 

                    /* Turunkan warna TR ke TD */
                    .fi-ta-table tbody tr td { background-color: inherit !important; }

                    /* 6. JURUS DEWA: ANTI BLOK TEKS SAAT DRAG */
                    body.is-dragging {
                        user-select: none !important;
                        -webkit-user-select: none !important;
                        cursor: grabbing !important;
                    }
                    body.is-dragging .fi-ta-content { pointer-events: none !important; }

                </style>'
            )

            // =======================================================
            // 2. HOOK: JAVASCRIPT GABUNGAN (WADAH SCROLL + FREEZE PAKSA)
            // =======================================================
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => '<script>
                    document.addEventListener("DOMContentLoaded", () => {
                        
                        function jalankanSistemGabungan() {
                            document.querySelectorAll("table.fi-ta-table").forEach(table => {
                                // --- A. PASANG WADAH SCROLL VERTICAL (Dari Kode 1) ---
                                const wrapper = table.parentElement;
                                if(wrapper && !wrapper.classList.contains("wadah-scroll-mantap")) {
                                    wrapper.classList.add("wadah-scroll-mantap");
                                }

                                // --- B. FREEZE 3 KOLOM SECARA MANUAL (Dari Kode 2) ---
                                const ths = table.querySelectorAll("thead tr th");
                                if (ths.length < 4) return; // Abaikan jika kolom sedikit

                                // Ambil lebar asli 2 kolom pertama
                                let w0 = ths[0].offsetWidth;
                                let w1 = ths[1].offsetWidth;

                                table.querySelectorAll("tr").forEach(row => {
                                    const cells = row.querySelectorAll("th, td");
                                    if (cells.length >= 3) {
                                        const isHeader = row.parentElement.tagName === "THEAD";
                                        
                                        // Header Freeze di 80 (paling atas), Isi Freeze di 30
                                        const zInd = isHeader ? "80" : "30"; 

                                        // Kunci Kolom 1
                                        cells[0].style.setProperty("position", "sticky", "important");
                                        cells[0].style.setProperty("left", "0px", "important");
                                        cells[0].style.setProperty("z-index", zInd, "important");

                                        // Kunci Kolom 2
                                        cells[1].style.setProperty("position", "sticky", "important");
                                        cells[1].style.setProperty("left", w0 + "px", "important");
                                        cells[1].style.setProperty("z-index", zInd, "important");

                                        // Kunci Kolom 3
                                        cells[2].style.setProperty("position", "sticky", "important");
                                        cells[2].style.setProperty("left", (w0 + w1) + "px", "important");
                                        cells[2].style.setProperty("z-index", zInd, "important");

                                        // Beri Garis Pembatas di Kolom 3
                                        let borderStyle = document.documentElement.classList.contains("dark") ? "2px solid #3f3f46" : "2px solid #cbd5e1";
                                        cells[2].style.setProperty("border-right", borderStyle, "important");
                                    }
                                });
                            });
                        }

                        // --- C. LOGIC DRAG TO SCROLL (ANTI BLOK TEKS) ---
                        let isDown = false; let startX; let startY; let scrollLeft; let scrollTop; let slider = null;
                        
                        document.addEventListener("mousedown", (e) => {
                            if (e.target.closest("button, a, input, select, textarea, .fi-dropdown-panel, .fi-modal")) return;

                            // Cari elemen dengan class wadah yang baru kita suntikkan
                            slider = e.target.closest(".wadah-scroll-mantap");
                            if (!slider) return;

                            isDown = true;
                            document.body.classList.add("is-dragging"); 
                            
                            startX = e.pageX - slider.offsetLeft;
                            startY = e.pageY - slider.offsetTop;
                            scrollLeft = slider.scrollLeft;
                            scrollTop = slider.scrollTop;
                        });

                        const stopDragging = () => {
                            isDown = false;
                            document.body.classList.remove("is-dragging");
                        };

                        document.addEventListener("mouseup", stopDragging);
                        document.addEventListener("mouseleave", stopDragging);

                        document.addEventListener("mousemove", (e) => {
                            if (!isDown || !slider) return;
                            e.preventDefault(); 
                            
                            const x = e.pageX - slider.offsetLeft;
                            const y = e.pageY - slider.offsetTop;
                            
                            const walkX = (x - startX) * 1.5;
                            const walkY = (y - startY) * 1.5;
                            
                            slider.scrollLeft = scrollLeft - walkX;
                            slider.scrollTop = scrollTop - walkY;
                        });

                        // Jalankan fungsi penggabungan berulang kali untuk mengatasi render dinamis Livewire
                        setInterval(jalankanSistemGabungan, 1000);
                        setTimeout(jalankanSistemGabungan, 200);
                        
                        window.addEventListener("resize", jalankanSistemGabungan);
                        document.addEventListener("livewire:navigated", jalankanSistemGabungan);
                    });
                </script>'
            )
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}