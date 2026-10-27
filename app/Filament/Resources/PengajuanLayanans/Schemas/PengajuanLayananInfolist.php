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
                        Section::make('Subdomain')
                            ->icon(Heroicon::OutlinedGlobeAlt)
                            ->columns(2)
                            ->visible(fn (PengajuanLayanan $record): bool => filled($record->rincian['subdomain'] ?? null))
                            ->schema([
                                self::rincian('subdomain')
                                    ->formatStateUsing(fn (string $state): string => "{$state}." . PengajuanLayanan::DOMAIN)
                                    ->copyable()
                                    ->weight('semibold')
                                    ->columnSpanFull(),
                                self::rincian('jenis_layanan')
                                    ->formatStateUsing(fn (string $state): string => PengajuanLayanan::JENIS_LAYANAN_DOMAIN[$state] ?? $state),
                                self::rincian('ip_tujuan')
                                    ->fontFamily('mono')
                                    ->copyable()
                                    ->placeholder('Tidak diisi (hosting disiapkan Diskominfo)'),
                                self::rincian('lokasi_server')->columnSpanFull(),
                                self::rincian('deskripsi')
                                    ->extraAttributes(['class' => 'whitespace-pre-line'])
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Pemohon')
                            ->icon(Heroicon::OutlinedUser)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('instansi')->label('Instansi')->columnSpanFull(),
                                TextEntry::make('nama_pemohon')
                                    ->label(fn (PengajuanLayanan $record): string => $record->layanan === 'subdomain-hosting' ? 'Penanggung jawab' : 'Nama'),
                                TextEntry::make('whatsapp')
                                    ->label('Nomor HP (WhatsApp)')
                                    ->copyable()
                                    ->icon(Heroicon::OutlinedChatBubbleOvalLeft)
                                    ->url(fn (PengajuanLayanan $record): string => $record->tautanWhatsapp(), shouldOpenInNewTab: true),
                                self::rincian('nik')->fontFamily('mono')->copyable(),
                                self::rincian('nip')->fontFamily('mono')->copyable(),
                                self::rincian('jabatan'),
                                TextEntry::make('ttl')
                                    ->label('Tempat, tanggal lahir')
                                    ->state(fn (PengajuanLayanan $record): ?string => $record->tempatTanggalLahir())
                                    ->visible(fn (PengajuanLayanan $record): bool => in_array('tanggal_lahir', PengajuanLayanan::JENIS[$record->layanan]['rincian'] ?? [], true))
                                    ->placeholder('–'),
                            ]),
                        Section::make('Berkas')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->columns(2)
                            ->schema([
                                // Berkas di disk privat, dibuka lewat rute admin di tab baru; PDF dan gambar tampil langsung di peramban.
                                self::berkas('surat', 'Surat permohonan', 'Buka surat'),
                                self::berkas('ktp', 'KTP', 'Buka KTP')
                                    ->visible(fn (PengajuanLayanan $record): bool => filled($record->ktp)),
                                // Isian lama, sebelum formulir punya rincian per layanan.
                                TextEntry::make('keterangan')
                                    ->label('Keterangan dari pemohon')
                                    ->extraAttributes(['class' => 'whitespace-pre-line'])
                                    ->visible(fn (PengajuanLayanan $record): bool => filled($record->keterangan))
                                    ->columnSpanFull(),
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

    /** Satu isian `rincian`; hanya tampil untuk layanan yang formulirnya memuat isian itu. */
    private static function rincian(string $kunci): TextEntry
    {
        return TextEntry::make("rincian.{$kunci}")
            ->label(PengajuanLayanan::LABEL_RINCIAN[$kunci])
            ->visible(fn (PengajuanLayanan $record): bool => in_array($kunci, PengajuanLayanan::JENIS[$record->layanan]['rincian'] ?? [], true))
            ->placeholder('–');
    }

    private static function berkas(string $kolom, string $label, string $tautan): TextEntry
    {
        return TextEntry::make($kolom)
            ->label($label)
            ->formatStateUsing(fn (string $state): string => "{$tautan} (" . strtoupper(pathinfo($state, PATHINFO_EXTENSION)) . ')')
            ->icon(Heroicon::OutlinedPaperClip)
            ->color('primary')
            ->weight('semibold')
            ->url(fn (PengajuanLayanan $record): string => route('filament.admin.pengajuan-berkas', [$record, $kolom]), shouldOpenInNewTab: true);
    }
}
