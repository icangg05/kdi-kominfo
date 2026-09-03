<?php

namespace App\Filament\Widgets;

use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Pegawai;
use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan';

    protected function getStats(): array
    {
        return [
            Stat::make('Berita', Berita::count())
                ->description(Berita::sum('total_lihat') . ' kali dibaca')
                ->color('primary'),
            Stat::make('Dokumen', Dokumen::count())
                ->description(Dokumen::sum('total_unduhan') . ' kali diunduh')
                ->color('primary'),
            Stat::make('Galeri', Galeri::count())
                ->description('foto terpublikasi'),
            Stat::make('Pegawai', Pegawai::count())
                ->description('terdaftar di profil'),
            Stat::make('Pengunjung hari ini', Visitor::whereDate('date', today())->count())
                ->description('total ' . Visitor::count() . ' sejak awal')
                ->color('success'),
        ];
    }
}
