<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Masuk;
use App\Filament\Pages\Auth\Profil;
use App\Filament\Widgets\RingkasanStats;
use App\Support\BackupDatabase;
use App\Support\WaktuWita;
use Filament\Enums\ThemeMode;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Masuk::class)
            ->profile(Profil::class, isSimple: false)
            ->brandName('Diskominfo Kendari')
            // Logo resmi bertulisan putih; topbar admin dibuat biru agar sesuai aturan logo di atas biru.
            ->brandLogo('/img/kominfo-logo.webp')
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            // Skala biru-* situs publik. Palet hasil Color::hex() hanya mengambil hue, sehingga shade 600
            // terlalu terang untuk teks putih. Abu-abu diberi rona biru; ujung gelapnya = mode gelap situs.
            ->colors([
                'primary' => $this->palet([
                    50 => '#f2f7fd', 100 => '#e2edfa', 200 => '#c0d8f4', 300 => '#8bb9ea', 400 => '#4f95dc', 500 => '#2777c8',
                    600 => '#0b4ea2', 700 => '#0d478e', 800 => '#0f3c75', 900 => '#0b2f5c', 950 => '#071f3d',
                ]),
                'gray' => $this->palet([
                    50 => '#f3f6fb', 100 => '#e7edf5', 200 => '#dbe3ef', 300 => '#bccadb', 400 => '#8c9db5', 500 => '#5f7290',
                    600 => '#475a76', 700 => '#33465f', 800 => '#1d3558', 900 => '#0c2344', 950 => '#05152b',
                ]),
            ])
            // IBM Plex Sans di-host situs Astro (/fonts); @font-face ada di view gaya admin.
            ->font('IBM Plex Sans', provider: LocalFontProvider::class)
            // Terang sebagai bawaan, bukan mengikuti sistem; pengguna tetap bisa ganti dari menu akun.
            ->defaultThemeMode(ThemeMode::Light)
            ->maxContentWidth(Width::Full)
            ->renderHook(PanelsRenderHook::STYLES_AFTER, fn () => view('filament.admin.gaya'))
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE, fn () => view('filament.admin.bilah-atas'))
            ->renderHook(PanelsRenderHook::BODY_END, fn () => view('filament.admin.skrip-jam'))
            ->renderHook(PanelsRenderHook::BODY_END, fn () => view('filament.admin.modal-backup'))
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups(['Konten', 'Profil Dinas', 'Pengaturan'])
            // Menu membuka modal konfirmasi (view modal-backup), baru tombol di dalamnya mengunduh.
            ->navigationItems([
                NavigationItem::make('Backup Database')
                    ->url('#backup-database')
                    ->icon(Heroicon::OutlinedCircleStack)
                    ->group('Pengaturan'),
            ])
            ->authenticatedRoutes(fn () => Route::get('backup-database', fn () => response()->streamDownload(
                fn () => BackupDatabase::tulis(fopen('php://output', 'w')),
                'backup-database-' . WaktuWita::sekarang()->format('Y-m-d-His') . '.sql',
                ['Content-Type' => 'application/sql'],
            ))->name('backup-database'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                RingkasanStats::class,
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

    /**
     * @param  array<int, string>  $hex
     * @return array<int, string>
     */
    private function palet(array $hex): array
    {
        return array_map(Color::convertToOklch(...), $hex);
    }
}
