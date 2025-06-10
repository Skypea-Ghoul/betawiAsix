<?php

namespace App\Providers\Filament;

use App\Filament\Resources\YesResource\Widgets\Area;
use App\Filament\Resources\YesResource\Widgets\Promo;
use App\Filament\Resources\YesResource\Widgets\QPersotoan;
use App\Filament\Resources\YesResource\Widgets\QProduct;
use App\Filament\Resources\YesResource\Widgets\QSoto;
use App\Filament\Resources\YesResource\Widgets\StockOverview as WidgetsStockOverview;
use App\Filament\Resources\YesResource\Widgets\TotalProduct;
use App\Filament\Widgets\QPersotoan as WidgetsQPersotoan;
use App\Filament\Widgets\StockOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->registration()
            ->login()
            ->colors([
                'primary' => Color::Amber,
                'indigo' => Color::Indigo,
                'sky' => Color::Sky,
                'violet' => Color::Violet,
                'fuchsia' => Color::Fuchsia,
                'rose' => Color::Rose,
                'neutral' => Color::Neutral,
            ])
            ->font('Poppins')
            ->favicon(asset('image/Logo.png'))
             ->plugins([
            FilamentApexChartsPlugin::make()
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // WidgetsQPersotoan::class,
                StockOverview::class,
                TotalProduct::class,
                Promo::class,
            ])
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
