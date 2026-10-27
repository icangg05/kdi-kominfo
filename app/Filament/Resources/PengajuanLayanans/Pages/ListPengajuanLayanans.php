<?php

namespace App\Filament\Resources\PengajuanLayanans\Pages;

use App\Filament\Resources\PengajuanLayanans\PengajuanLayananResource;
use App\Models\PengajuanLayanan;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPengajuanLayanans extends ListRecords
{
    protected static string $resource = PengajuanLayananResource::class;

    public function getSubheading(): ?string
    {
        return 'Pengajuan subdomain, email dinas, dan TTE yang dikirim OPD lewat situs. Buka satu pengajuan untuk mencocokkan data dengan surat (dan KTP untuk TTE), lalu setujui atau tolak.';
    }

    /** Tab per status; "Perlu ditindak" (menunggu + diproses) jadi bawaan supaya antrean kerja langsung terlihat. */
    public function getTabs(): array
    {
        $jumlah = PengajuanLayanan::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return [
            'perlu' => Tab::make('Perlu ditindak')
                ->badge(($jumlah['menunggu'] ?? 0) + ($jumlah['diproses'] ?? 0) ?: null)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['menunggu', 'diproses'])),
            'disetujui' => Tab::make('Disetujui')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'disetujui')),
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'ditolak')),
            'semua' => Tab::make('Semua'),
        ];
    }
}
