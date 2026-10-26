<?php

namespace App\Filament\Resources\PengajuanLayanans\Schemas;

use App\Filament\Resources\PengajuanLayanans\Tables\PengajuanLayanansTable;
use App\Models\PengajuanLayanan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PengajuanLayananInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Surat permohonan')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->description('Rincian kebutuhan (nama subdomain, daftar akun email, pejabat TTE) ada di dalam surat.')
                            ->schema([
                                // Surat di disk privat, dibuka lewat rute admin di tab baru; PDF dan gambar tampil langsung di peramban.
                                TextEntry::make('surat')
                                    ->label('Berkas surat')
                                    ->formatStateUsing(fn (string $state): string => 'Buka surat (' . strtoupper(pathinfo($state, PATHINFO_EXTENSION)) . ')')
                                    ->icon(Heroicon::OutlinedPaperClip)
                                    ->color('primary')
                                    ->weight('semibold')
                                    ->url(fn (PengajuanLayanan $record): string => route('filament.admin.pengajuan-surat', $record), shouldOpenInNewTab: true),
                                TextEntry::make('keterangan')
                                    ->label('Keterangan dari pemohon')
                                    ->extraAttributes(['class' => 'whitespace-pre-line'])
                                    ->placeholder('Tidak ada keterangan'),
                            ]),
                        Section::make('Pemohon')
                            ->icon(Heroicon::OutlinedUser)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('instansi')->label('Perangkat daerah')->columnSpanFull(),
                                TextEntry::make('nama_pemohon')->label('Nama'),
                                TextEntry::make('whatsapp')
                                    ->label('WhatsApp')
                                    ->copyable()
                                    ->icon(Heroicon::OutlinedChatBubbleOvalLeft)
                                    ->url(fn (PengajuanLayanan $record): string => $record->tautanWhatsapp(), shouldOpenInNewTab: true),
                            ]),
                    ]),
                Section::make('Status')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => PengajuanLayanan::STATUS[$state] ?? $state)
                            ->color(fn (string $state): string => PengajuanLayanansTable::WARNA_STATUS[$state] ?? 'gray'),
                        TextEntry::make('layanan')
                            ->formatStateUsing(fn (string $state): string => PengajuanLayanan::JENIS[$state]['label'] ?? $state),
                        TextEntry::make('created_at')->label('Masuk')->dateTime('d F Y, H.i'),
                        TextEntry::make('catatan_admin')
                            ->label('Catatan untuk pemohon')
                            ->extraAttributes(['class' => 'whitespace-pre-line'])
                            ->placeholder('Belum ada catatan'),
                        TextEntry::make('verifikator.name')
                            ->label('Diverifikasi oleh')
                            ->placeholder('–'),
                        TextEntry::make('diverifikasi_pada')
                            ->label('Diputuskan')
                            ->dateTime('d F Y, H.i')
                            ->placeholder('–'),
                    ]),
            ]);
    }
}
