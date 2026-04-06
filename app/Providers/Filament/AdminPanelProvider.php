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
            // TEMA WARNA: MERAH TUA & HITAM ELEGAN
            // =======================================================
            ->colors([
                'primary' => Color::hex('#7f1d1d'),
                'danger'  => Color::Rose,
                'warning' => Color::Amber,
                'success' => Color::Emerald,
                'info'    => Color::Sky,
                'gray'    => Color::Zinc,
            ])
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            
            ->navigationGroups([
                'Manajemen Perangkat',
                'Monitoring',
                'Operasional Perangkat',
                'Maintenance Perangkat',
                'Data Master',
            ])
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            
            // =======================================================
            // 1. RENDER HOOK: CSS GLASSMORPHISM & WARNA PREMIUM
            // =======================================================
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<style>
                    aside .fi-sidebar-header, aside .fi-sidebar-footer { display: none !important; }

                    .fi-ta-content { overflow: visible !important; }

                    /* =========================================
                       SIDEBAR COLOR INJECTION
                       ========================================= */
                    .fi-sidebar {
                        background: linear-gradient(180deg, rgba(255, 255, 255, 0.95) 0%, rgba(254, 242, 242, 0.95) 100%) !important;
                    }
                    .dark .fi-sidebar {
                        background: linear-gradient(180deg, rgba(15, 15, 15, 0.95) 0%, rgba(30, 5, 5, 0.95) 100%) !important;
                        border-right: 1px solid rgba(153, 27, 27, 0.2) !important;
                    }
                    .fi-sidebar-item.fi-active > a {
                        background: linear-gradient(90deg, rgba(153, 27, 27, 0.1) 0%, transparent 100%) !important;
                        border-left: 4px solid #991b1b !important;
                    }
                    .dark .fi-sidebar-item.fi-active > a {
                        background: linear-gradient(90deg, rgba(220, 38, 38, 0.15) 0%, transparent 100%) !important;
                        border-left: 4px solid #dc2626 !important;
                    }

                    /* =========================================
                       WADAH TABEL
                       ========================================= */
                    .wadah-scroll-mantap {
                        display: block !important;
                        max-height: 55vh !important; 
                        overflow-y: auto !important;
                        overflow-x: auto !important;
                        position: relative !important;
                        width: 100% !important;
                        border-radius: 12px;
                        box-shadow: inset 0 0 20px rgba(0,0,0,0.02);
                    }

                    .wadah-scroll-mantap::-webkit-scrollbar { width: 8px; height: 8px; }
                    .wadah-scroll-mantap::-webkit-scrollbar-track { background: rgba(0,0,0,0.02); border-radius: 4px; }
                    .wadah-scroll-mantap::-webkit-scrollbar-thumb { background: rgba(161, 161, 170, 0.5); border-radius: 4px; transition: all 0.3s ease; }
                    .wadah-scroll-mantap::-webkit-scrollbar-thumb:hover { background: rgba(127, 29, 29, 0.8); }
                    .dark .wadah-scroll-mantap::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
                    .dark .wadah-scroll-mantap::-webkit-scrollbar-thumb { background: rgba(127, 29, 29, 0.4); }
                    .dark .wadah-scroll-mantap::-webkit-scrollbar-thumb:hover { background: rgba(220, 38, 38, 0.8); }

                    table.fi-ta-table {
                        border-collapse: separate !important;
                        border-spacing: 0 !important;
                        width: 100% !important;
                    }
                    .fi-ta-table, .fi-ta-table thead, .fi-ta-table tbody, .fi-ta-table tr {
                        position: static !important;
                        transform: none !important;
                        will-change: auto !important;
                    }

                    /* STICKY HEADER GLASSMORPHISM */
                    .fi-ta-table thead tr th {
                        position: sticky !important;
                        top: 0 !important;
                        z-index: 10 !important;
                        background: linear-gradient(135deg, rgba(153, 27, 27, 0.85) 0%, rgba(69, 10, 10, 0.95) 100%) !important;
                        backdrop-filter: blur(12px) !important;
                        -webkit-backdrop-filter: blur(12px) !important;
                        border-bottom: 2px solid rgba(255,255,255,0.1) !important;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
                        padding-top: 12px !important; padding-bottom: 12px !important;
                        transition: all 0.3s ease;
                    }
                    .fi-ta-table thead tr th *, .fi-ta-table thead tr th button span, .fi-ta-table thead tr th svg {
                        color: rgba(255,255,255,0.95) !important; opacity: 1 !important; font-weight: 600 !important; text-shadow: 0 1px 2px rgba(0,0,0,0.2);
                    }
                    .dark .fi-ta-table thead tr th {
                        background: linear-gradient(135deg, rgba(69, 10, 10, 0.85) 0%, rgba(0, 0, 0, 0.95) 100%) !important;
                        border-bottom: 2px solid rgba(127,29,29,0.3) !important;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.4) !important;
                    }

                    /* BACKGROUND SEL TABEL (SOLID COLOR / ANTI TEMBUS) */
                    .fi-ta-table tbody tr td { 
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important; 
                        background-color: #ffffff !important; 
                        border-bottom: 1px solid rgba(153,27,27,0.05) !important; 
                    }
                    .fi-ta-table tbody tr:nth-child(even) td { background-color: #fef8f8 !important; } 
                    .fi-ta-table tbody tr:hover td { background-color: #fee2e2 !important; } 
                    
                    .dark .fi-ta-table tbody tr td { 
                        background-color: #120404 !important; 
                        border-bottom: 1px solid rgba(153,27,27,0.1) !important; 
                    }
                    .dark .fi-ta-table tbody tr:nth-child(even) td { background-color: #1a0505 !important; } 
                    .dark .fi-ta-table tbody tr:hover td { background-color: #380b0b !important; } 

                    /* =========================================
                       POPUP, MODAL & DROPDOWN VIP STYLING
                       ========================================= */
                    /* Efek Kaca Premium untuk Jendela Modal & Dropdown */
                    .fi-modal-window, .fi-dropdown-panel {
                        background: rgba(255, 255, 255, 0.85) !important;
                        backdrop-filter: blur(20px) !important;
                        -webkit-backdrop-filter: blur(20px) !important;
                        border: 1px solid rgba(153, 27, 27, 0.15) !important;
                        box-shadow: 0 25px 50px -12px rgba(153, 27, 27, 0.25), 0 0 15px rgba(0,0,0,0.05) !important;
                        border-radius: 16px !important;
                    }

                    /* Header & Footer Pop-up Light Mode */
                    .fi-modal-header { border-bottom: 1px solid rgba(153, 27, 27, 0.1) !important; background: rgba(255, 255, 255, 0.5) !important; }
                    .fi-modal-footer { border-top: 1px solid rgba(153, 27, 27, 0.1) !important; background: rgba(250, 245, 245, 0.6) !important; }

                    /* DARK MODE Pop-up (Merah Gelap Mewah layaknya Kasino VIP) */
                    .dark .fi-modal-window, .dark .fi-dropdown-panel {
                        background: linear-gradient(145deg, rgba(25, 4, 4, 0.95) 0%, rgba(10, 0, 0, 0.98) 100%) !important;
                        border: 1px solid rgba(220, 38, 38, 0.2) !important;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), inset 0 1px 0 rgba(255,255,255,0.05), 0 0 30px rgba(153, 27, 27, 0.2) !important;
                    }

                    /* Header & Footer Pop-up Dark Mode */
                    .dark .fi-modal-header { border-bottom: 1px solid rgba(220, 38, 38, 0.2) !important; background: rgba(0, 0, 0, 0.3) !important; }
                    .dark .fi-modal-footer { border-top: 1px solid rgba(220, 38, 38, 0.2) !important; background: rgba(0, 0, 0, 0.4) !important; }

                    /* Efek Dragging */
                    body.is-dragging {
                        user-select: none !important;
                        -webkit-user-select: none !important;
                        cursor: grabbing !important;
                    }
                    body.is-dragging .fi-ta-content { pointer-events: none !important; }
                    
                    /* =========================================
                       WIDGET STATS GLASSMORPHISM (PREMIUM)
                       ========================================= */
                    /* Menghilangkan background default putih/hitam */
                    .fi-wi-stats-overview-stat {
                        background: rgba(255, 255, 255, 0.1) !important;
                        backdrop-filter: blur(10px) !important;
                        border: 1px solid rgba(255, 255, 255, 0.1) !important;
                        transition: all 0.3s ease !important;
                    }
                    
                    /* Warna Khusus Per Kotak (Light Mode) */
                    .fi-wi-stats-overview-stat:nth-child(1) { background: rgba(153, 27, 27, 0.05) !important; border-bottom: 4px solid #991b1b !important; } /* Total - Merah */
                    .fi-wi-stats-overview-stat:nth-child(2) { background: rgba(16, 185, 129, 0.05) !important; border-bottom: 4px solid #10b981 !important; } /* Aktif - Hijau */
                    .fi-wi-stats-overview-stat:nth-child(3) { background: rgba(245, 158, 11, 0.05) !important; border-bottom: 4px solid #f59e0b !important; } /* Rusak - Kuning */
                    .fi-wi-stats-overview-stat:nth-child(4) { background: rgba(225, 29, 72, 0.05) !important; border-bottom: 4px solid #e11d48 !important; } /* Non-Aktif - Rose */
                    
                    /* Efek Hover Agar Kotak "Menyala" */
                    .fi-wi-stats-overview-stat:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
                    
                    /* MEWARNAI ICON (Sesuai warna aslinya) */
                    .fi-wi-stats-overview-stat:nth-child(1) .fi-wi-stats-overview-stat-icon { color: #991b1b !important; filter: drop-shadow(0 0 5px rgba(153,27,27,0.3)); }
                    .fi-wi-stats-overview-stat:nth-child(2) .fi-wi-stats-overview-stat-icon { color: #10b981 !important; filter: drop-shadow(0 0 5px rgba(16,185,129,0.3)); }
                    .fi-wi-stats-overview-stat:nth-child(3) .fi-wi-stats-overview-stat-icon { color: #f59e0b !important; filter: drop-shadow(0 0 5px rgba(245,158,11,0.3)); }
                    .fi-wi-stats-overview-stat:nth-child(4) .fi-wi-stats-overview-stat-icon { color: #e11d48 !important; filter: drop-shadow(0 0 5px rgba(225,29,72,0.3)); }
                    
                    /* DARK MODE OVERRIDES (Lebap, Pekat, Glowing) */
                    .dark .fi-wi-stats-overview-stat { border: 1px solid rgba(255, 255, 255, 0.05) !important; }
                    .dark .fi-wi-stats-overview-stat:nth-child(1) { background: rgba(153, 27, 27, 0.15) !important; }
                    .dark .fi-wi-stats-overview-stat:nth-child(2) { background: rgba(16, 185, 129, 0.15) !important; }
                    .dark .fi-wi-stats-overview-stat:nth-child(3) { background: rgba(245, 158, 11, 0.15) !important; }
                    .dark .fi-wi-stats-overview-stat:nth-child(4) { background: rgba(225, 29, 72, 0.15) !important; }

                </style>'
            )

            // =======================================================
            // 2. HOOK: JS (TETAP AMAN & RESPONSIVE FREEZE)
            // =======================================================
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => '<script>
                    document.addEventListener("DOMContentLoaded", () => {
                        
                        function jalankanSistemGabungan() {
                            const isDesktop = window.innerWidth >= 1024;

                            document.querySelectorAll("table.fi-ta-table").forEach(table => {
                                const wrapper = table.parentElement;
                                if(wrapper && !wrapper.classList.contains("wadah-scroll-mantap")) {
                                    wrapper.classList.add("wadah-scroll-mantap");
                                }

                                const ths = table.querySelectorAll("thead tr th");
                                if (ths.length < 4) return; 

                                let w0 = ths[0].offsetWidth;
                                let w1 = ths[1].offsetWidth;

                                table.querySelectorAll("tr").forEach(row => {
                                    const cells = row.querySelectorAll("th, td");
                                    if (cells.length >= 3) {
                                        const isHeader = row.parentElement.tagName === "THEAD";
                                        
                                        if (isDesktop) {
                                            const zInd = isHeader ? "15" : "5"; 

                                            cells[0].style.setProperty("position", "sticky", "important");
                                            cells[0].style.setProperty("left", "0px", "important");
                                            cells[0].style.setProperty("z-index", zInd, "important");

                                            cells[1].style.setProperty("position", "sticky", "important");
                                            cells[1].style.setProperty("left", w0 + "px", "important");
                                            cells[1].style.setProperty("z-index", zInd, "important");

                                            cells[2].style.setProperty("position", "sticky", "important");
                                            cells[2].style.setProperty("left", (w0 + w1) + "px", "important");
                                            cells[2].style.setProperty("z-index", zInd, "important");

                                            let shadowStyle = document.documentElement.classList.contains("dark") 
                                                ? "4px 0 10px -2px rgba(0,0,0,0.6)" 
                                                : "4px 0 10px -2px rgba(0,0,0,0.08)";
                                            cells[2].style.setProperty("box-shadow", shadowStyle, "important");
                                            cells[2].style.setProperty("border-right", "none", "important");
                                        } else {
                                            if (!isHeader) {
                                                cells[0].style.removeProperty("position");
                                                cells[1].style.removeProperty("position");
                                                cells[2].style.removeProperty("position");
                                                
                                                cells[0].style.removeProperty("z-index");
                                                cells[1].style.removeProperty("z-index");
                                                cells[2].style.removeProperty("z-index");
                                            } else {
                                                cells[0].style.setProperty("z-index", "10", "important");
                                                cells[1].style.setProperty("z-index", "10", "important");
                                                cells[2].style.setProperty("z-index", "10", "important");
                                            }

                                            cells[0].style.removeProperty("left");
                                            cells[1].style.removeProperty("left");
                                            cells[2].style.removeProperty("left");
                                            cells[2].style.removeProperty("box-shadow");
                                        }
                                    }
                                });
                            });
                        }

                        let isDown = false; let startX; let startY; let scrollLeft; let scrollTop; let slider = null;
                        
                        document.addEventListener("mousedown", (e) => {
                            if (e.target.closest("button, a, input, select, textarea, .fi-dropdown-panel, .fi-modal")) return;

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