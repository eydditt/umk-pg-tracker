<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;


class AdminPanelProvider extends PanelProvider
{
    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->passwordReset()
            ->brandName('UMK PG Tracker')
            ->colors([
                'primary' => Color::hex('#2A9D8F'),
            ])
            
           
            ->renderHook(
                    PanelsRenderHook::HEAD_END,
                    fn() => Blade::render('
                        <style>
                            .fi-simple-main input[type="email"],
                            .fi-simple-main input[type="password"],
                            .fi-simple-main input[type="text"] {
                                color-scheme: dark !important;
                                background: rgba(255,255,255,0.15) !important;
                                color: #ffffff !important;
                                -webkit-text-fill-color: #ffffff !important;
                                caret-color: #ffffff !important;
                                border: 1.5px solid rgba(255,255,255,0.35) !important;
                                border-radius: 0.75rem !important;
                            }

                            @media print {
                                @page {
                                    margin: 1cm 1.5cm;
                                    size: A4 landscape;
                                }

                                * {
                                    -webkit-print-color-adjust: exact !important;
                                    print-color-adjust: exact !important;
                                }

                                body, html {
                                    font-size: 11px !important;
                                }

                                /* Hide chrome */
                                .fi-sidebar,
                                .fi-topbar,
                                .fi-topbar-ctn,
                                .fi-header-actions-ctn,
                                .fi-breadcrumbs,
                                .fi-sidebar-close-overlay,
                                .fi-page-header-main-ctn h1 {
                                    display: none !important;
                                }

                                /* Print report header */
                                .fi-page-header-main-ctn::before {
                                    content: "UMK PG Tracker — Dashboard Report";
                                    display: block;
                                    text-align: center;
                                    font-size: 15px;
                                    font-weight: 700;
                                    color: #1a3a38;
                                    border-bottom: 2px solid #2A9D8F;
                                    padding-bottom: 6px;
                                    margin-bottom: 10px;
                                }

                                .fi-page-header-main-ctn::after {
                                    content: attr(data-print-date);
                                    display: block;
                                    text-align: right;
                                    font-size: 9px;
                                    color: #666;
                                    margin-top: 4px;
                                    margin-bottom: 8px;
                                }

                                /* Full width layout */
                                .fi-main-ctn,
                                .fi-main {
                                    margin: 0 !important;
                                    padding: 0 !important;
                                    max-width: 100% !important;
                                    width: 100% !important;
                                }

                                /* Keep 4-column grid */
                                .fi-page-content {
                                    display: grid !important;
                                    grid-template-columns: repeat(4, 1fr) !important;
                                    gap: 6px !important;
                                }

                                /* Compact all widgets */
                                .fi-wi {
                                    break-inside: avoid !important;
                                    margin: 0 !important;
                                }

                                /* Section padding */
                                .fi-section-content-ctn,
                                .fi-section {
                                    padding: 6px !important;
                                }

                                /* Stats cards compact */
                                .fi-wi-stats-overview-stat {
                                    padding: 8px !important;
                                }

                                .fi-wi-stats-overview-stat-value {
                                    font-size: 18px !important;
                                }

                                .fi-wi-stats-overview-stat-label,
                                .fi-wi-stats-overview-stat-description {
                                    font-size: 9px !important;
                                }

                                /* Chart headings */
                                .fi-section-header-heading {
                                    font-size: 10px !important;
                                    font-weight: 600 !important;
                                }

                                /* Shrink canvas charts */
                                canvas {
                                    max-height: 140px !important;
                                    width: 100% !important;
                                }

                                /* Greeting compact */
                                .fi-wi:first-child {
                                    padding: 6px !important;
                                    font-size: 10px !important;
                                }
                            }
                        </style>
                    ')
                )
           ->renderHook(
                    PanelsRenderHook::BODY_END,
                    fn() => Blade::render('
                        <script>
                        (function() {
                            const slides = [
                                "/images/slideshow/umk 1.jpg",
                                "/images/slideshow/alumni_universiti_malaysia_kelantan_cover.jpeg",
                                "/images/slideshow/b21150030c8c7dcd51af7447e659490e.jpg"
                            ];
                            let current = 0;
                            let next = 1;

                            const layout = document.querySelector(".fi-simple-layout");
                            if (!layout) return;

                            slides.forEach(src => { const img = new Image(); img.src = src; });

                            const createLayer = (src, z) => {
                                const el = document.createElement("div");
                                el.style.cssText = `
                                    position: fixed;
                                    inset: 0;
                                    z-index: ${z};
                                    background: url("${src}") center/cover no-repeat;
                                    transition: opacity 1.5s ease-in-out;
                                    pointer-events: none;
                                `;
                                return el;
                            };

                            const layerA = createLayer(slides[0], 0);
                            const layerB = createLayer(slides[1], 0);
                            layerB.style.opacity = "0";

                            layout.prepend(layerB);
                            layout.prepend(layerA);

                            const mainCtn = document.querySelector(".fi-simple-main-ctn");
                            if (mainCtn) mainCtn.style.position = "relative";
                            if (mainCtn) mainCtn.style.zIndex = "10";

                            let showingA = true;

                            setInterval(() => {
                                next = (current + 1) % slides.length;
                                if (showingA) {
                                    layerB.style.backgroundImage = `url("${slides[next]}")`;
                                    layerB.style.opacity = "1";
                                    layerA.style.opacity = "0";
                                } else {
                                    layerA.style.backgroundImage = `url("${slides[next]}")`;
                                    layerA.style.opacity = "1";
                                    layerB.style.opacity = "0";
                                }
                                showingA = !showingA;
                                current = next;
                            }, 5000);
                        })();

                        // ── PRINT DASHBOARD ──
                        window.printDashboard = function() {
                            document.body.setAttribute(
                                "data-print-date",
                                new Date().toLocaleString("en-MY", {
                                    dateStyle: "full",
                                    timeStyle: "short"
                                })
                            );
                            window.print();
                        };

                        window.printDashboard = function() {
                        const now = new Date().toLocaleString("en-MY", {
                            dateStyle: "full",
                            timeStyle: "short"
                        });
                        const header = document.querySelector(".fi-page-header-main-ctn");
                        if (header) header.setAttribute("data-print-date", "Printed: " + now);
                        window.print();
                    };

                        </script>
                    ')
                )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn() => Blade::render('
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 1.25rem; margin-bottom: 1rem;">
                            <img src="/images/logo/logoumk.png" alt="UMK Logo"
                                style="height: 64px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.4));">
                            <img src="/images/logo/logofsdk.png" alt="FSDK Logo"
                                style="height: 100px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.4)) brightness(1.1);">
                        </div>
                        <h2 style="color: #ffffff; font-size: 1.3rem; font-weight: 700; margin: 0 0 0.5rem; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">
                            UMK PG Tracker
                        </h2>
                        <p style="color: rgba(255,255,255,0.65); font-size: 0.78rem; font-weight: 400; margin: 0; letter-spacing: 0.06em; text-transform: uppercase;">
                            Administrative Portal &mdash; Postgraduate Student Management
                        </p>
                    </div>
                ')
            )
            ->renderHook(
                    PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_BEFORE,
                    fn() => Blade::render('
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <h2 style="color: #ffffff; font-size: 1.3rem; font-weight: 700; margin: 0 0 0.5rem; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">
                                🔐 Reset Your Password
                            </h2>
                            <p style="color: rgba(255,255,255,0.7); font-size: 0.83rem; margin: 0 0 0.4rem; line-height: 1.6;">
                                Enter your registered email address below.<br>
                                We will send you a secure password reset link.
                            </p>
                            <p style="color: rgba(255,255,255,0.45); font-size: 0.75rem; margin: 0;">
                                ⏱ The reset link will expire in
                                <strong style="color: #2A9D8F;">60 minutes</strong>.
                            </p>
                        </div>
                    ')
                )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
                 
            ])
          
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

            
    }

    
}