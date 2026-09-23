<?php

namespace App\Filament\Widgets;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Pegawai;
use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class RingkasanStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan';

    protected function getStats(): array
    {
        // Pemisah ribuan mengikuti locale aplikasi (id: 12.345). sum() dari MySQL bisa berupa string.
        $angka = fn (int|float|string|null $n): string => Number::format((int) $n, locale: app()->getLocale());

        return [
            Stat::make('Berita', $angka(Berita::count()))
                ->description($angka(Berita::sum('total_lihat')) . ' kali dibaca')
                ->color('primary'),
            Stat::make('Dokumen', $angka(Dokumen::count()))
                ->description($angka(Dokumen::sum('total_unduhan')) . ' kali diunduh')
                ->color('primary'),
            Stat::make('Galeri', $angka(Galeri::count()))
                ->description('foto terpublikasi'),
            Stat::make('Pegawai', $angka(Pegawai::count()))
                ->description('terdaftar di profil'),
            Stat::make('Pengunjung hari ini', $angka(Visitor::whereDate('date', today())->count()))
                ->description('total ' . $angka(Visitor::count()) . ' sejak awal')
                ->color('success'),
        ];
    }
}
