<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LowStockWidget;
use App\Filament\Widgets\RecentMovementsWidget;
use App\Filament\Widgets\RecentPurchasesWidget;
use App\Filament\Widgets\RecentSalesWidget;
use App\Filament\Widgets\StockStatsOverview;
use App\Filament\Widgets\StockflowHeroWidget;
use App\Filament\Widgets\TopProductsWidget;
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
            ->login()
            ->passwordReset()
            ->spa()
            ->brandName('StockFlow Maroc')
            ->brandLogo(asset('images/stockflow-logo.svg'))
            ->brandLogoHeight('2.75rem')
            ->theme(asset('css/filament/admin/theme.css') . '?v=' . filemtime(public_path('css/filament/admin/theme.css')))
            ->sidebarWidth('18rem')
            ->colors([
                'primary' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger' => Color::Red,
                'gray' => Color::Slate,
            ])
            ->navigationGroups([
                'Stock',
                'Achats & Ventes',
                'Contacts',
                'Rapports',
                'Administration',
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn (): string => '<a href="/admin" class="flex-shrink-0 ml-2">
                    <img src="'.asset('images/stockflow-logo.svg').'" alt="StockFlow Maroc" class="h-9 object-contain">
                </a>',
            )
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => view('filament.hooks.topbar-search')->render(),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): string => view('filament.hooks.topbar-actions')->render(),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '<style>
                    /* Hide Filament default logos */
                    .fi-topbar .fi-logo, 
                    .fi-sidebar-header { 
                        display: none !important; 
                    }
                    /* Search bar styling */
                    .stockflow-topbar-search {
                        display: flex;
                        align-items: center;
                        gap: 0.85rem;
                        width: 400px;
                        max-width: 40vw;
                        height: 2.75rem;
                        border: 1px solid var(--sf-border, #e2e8f0);
                        border-radius: 8px;
                        background: #f8fafc;
                        color: #64748b;
                        padding: 0 1rem;
                        margin: 0 auto;
                        transition: all 0.2s;
                    }
                    .stockflow-topbar-search:hover {
                        border-color: #cbd5e1;
                        background: #ffffff;
                    }
                    /* Fix Active Sidebar Navigation Item Contrast */
                    html body .fi-sidebar-item.fi-active > a,
                    html body .fi-sidebar-item.fi-active > button,
                    html body .fi-active > a,
                    html body .fi-active > button {
                        background: linear-gradient(135deg, #2563eb, #1e40af) !important;
                        color: #ffffff !important;
                        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4) !important;
                    }
                    html body .fi-sidebar-item.fi-active > a *,
                    html body .fi-sidebar-item.fi-active > button *,
                    html body .fi-active > a *,
                    html body .fi-active > button *,
                    html body .fi-active span,
                    html body .fi-sidebar-item.fi-active span {
                        color: #ffffff !important;
                        font-weight: 600 !important;
                    }
                </style>',
            )
            ->renderHook(
                PanelsRenderHook::PAGE_START,
                fn (): string => request()->is('admin')
                    ? view('filament.hooks.dashboard-heading')->render()
                    : '',
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\PosCaisse::class,
                \App\Filament\Pages\WhatsappConnection::class,
                \App\Filament\Pages\PrinterManagement::class,
            ])
            ->widgets([
                StockflowHeroWidget::class,
                StockStatsOverview::class,
                LowStockWidget::class,
                RecentMovementsWidget::class,
                TopProductsWidget::class,
                RecentSalesWidget::class,
                RecentPurchasesWidget::class,
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
            ->authMiddleware([Authenticate::class]);
    }
}
