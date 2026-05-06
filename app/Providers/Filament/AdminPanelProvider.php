<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
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
            ->brandName('KICAU MANIA ')
            ->colors([
                'primary' => Color::hex('#2A9D8F'),
            ])
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
                        <p style="color: #94a3b8; font-size: 0.8rem; font-weight: 400; margin: 0; letter-spacing: 0.04em; text-transform: uppercase;">
                            Administrative Portal &mdash; Postgraduate Student Management
                        </p>
                    </div>
                ')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\GreetingWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
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