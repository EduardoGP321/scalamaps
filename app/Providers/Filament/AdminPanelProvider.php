<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Elemind\FilamentECharts\FilamentEChartsPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->defaultThemeMode(ThemeMode::Dark)
            ->darkMode(false)
            ->colors([
                'primary' => Color::hex('#F7BE00'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->databaseNotifications()
            ->plugins([
                FilamentEChartsPlugin::make(),
                FilamentShieldPlugin::make()
                    ->scopeToTenant(false)
                    ->registerNavigation(true)
                    ->navigationGroup('Administración'),
                AuthDesignerPlugin::make()
                    ->login(
                        fn($config) => $config
                            ->media(asset('\login\imagen_mapa.webp'))
                            ->mediaPosition(MediaPosition::Cover)
                    ),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
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
            ])
            ->renderHook(
                'panels::head.end',
                fn() => new HtmlString('
                <style>
                    html {
                        background-color: #09090b !important; /* Evita el flashazo blanco */
                    }
                    .fi-theme-switcher {
                        display: none !important;
                    }
                </style>

                <script>
                    localStorage.setItem("theme", "dark");
                    document.documentElement.classList.add("dark");
                </script>
            ')
            );
    }
}
