<?php

namespace App\Filament\Resources\PengajuanLayanans;

use App\Models\PengajuanLayanan;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

/** Aksi verifikasi yang dipakai di tabel dan di halaman rincian pengajuan. */
final class PengajuanLayananAksi
{
    /** @return array<int, Action> */
    public static function semua(): array
    {
        return [self::proses(), self::setujui(), self::tolak(), self::catatan()];
    }

    public static function proses(): Action
    {
        return Action::make('proses')
            ->label('Proses')
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('info')
            ->visible(fn (PengajuanLayanan $record): bool => $record->status === 'menunggu')
            ->requiresConfirmation()
            ->modalHeading('Tandai sedang diproses?')
            ->modalDescription('Pemohon akan melihat status "Sedang diproses" saat mengecek pengajuannya.')
            ->action(fn (PengajuanLayanan $record) => self::ubah($record, 'diproses', $record->catatan_admin));
    }

    public static function setujui(): Action
    {
        return Action::make('setujui')
            ->label('Setujui')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->color('success')
            ->visible(fn (PengajuanLayanan $record): bool => in_array($record->status, ['menunggu', 'diproses'], true))
            ->modalHeading('Setujui pengajuan')
            ->modalSubmitActionLabel('Setujui')
            ->schema([
                Textarea::make('catatan_admin')
                    ->label('Catatan untuk pemohon')
                    ->placeholder('Mis. subdomain dinkes.kendarikota.go.id sudah aktif; akses panel dikirim ke email pemohon.')
                    ->helperText('Opsional. Terlihat oleh pemohon di halaman cek status, jadi jangan tulis kata sandi di sini.')
                    ->rows(4),
            ])
            ->fillForm(fn (PengajuanLayanan $record): array => ['catatan_admin' => $record->catatan_admin])
            ->action(fn (PengajuanLayanan $record, array $data) => self::ubah($record, 'disetujui', $data['catatan_admin'] ?? null));
    }

    public static function tolak(): Action
    {
        return Action::make('tolak')
            ->label('Tolak')
            ->icon(Heroicon::OutlinedXCircle)
            ->color('danger')
            ->visible(fn (PengajuanLayanan $record): bool => in_array($record->status, ['menunggu', 'diproses'], true))
            ->modalHeading('Tolak pengajuan')
            ->modalSubmitActionLabel('Tolak')
            ->schema([
                Textarea::make('catatan_admin')
                    ->label('Alasan penolakan')
                    ->placeholder('Mis. surat belum ditandatangani pimpinan OPD. Silakan ajukan ulang dengan surat yang sudah ditandatangani.')
                    ->helperText('Wajib. Terlihat oleh pemohon di halaman cek status.')
                    ->required()
                    ->rows(4),
            ])
            ->fillForm(fn (PengajuanLayanan $record): array => ['catatan_admin' => $record->catatan_admin])
            ->action(fn (PengajuanLayanan $record, array $data) => self::ubah($record, 'ditolak', $data['catatan_admin']));
    }

    /** Catatan tetap bisa diubah setelah keputusan, mis. menambah informasi akun yang sudah dibuat. */
    public static function catatan(): Action
    {
        return Action::make('catatan')
            ->label('Ubah catatan')
            ->icon(Heroicon::OutlinedChatBubbleLeftEllipsis)
            ->color('gray')
            ->visible(fn (PengajuanLayanan $record): bool => in_array($record->status, ['disetujui', 'ditolak'], true))
            ->schema([
                Textarea::make('catatan_admin')
                    ->label('Catatan untuk pemohon')
                    ->helperText('Terlihat oleh pemohon di halaman cek status.')
                    ->required(fn (PengajuanLayanan $record): bool => $record->status === 'ditolak')
                    ->rows(4),
            ])
            ->fillForm(fn (PengajuanLayanan $record): array => ['catatan_admin' => $record->catatan_admin])
            ->action(function (PengajuanLayanan $record, array $data) {
                $record->update(['catatan_admin' => $data['catatan_admin'] ?: null]);
                Notification::make()->title('Catatan disimpan')->success()->send();
            });
    }

    private static function ubah(PengajuanLayanan $record, string $status, ?string $catatan): void
    {
        $record->update([
            'status' => $status,
            'catatan_admin' => $catatan ?: null,
            'diverifikasi_oleh' => auth()->id(),
            // Waktu keputusan; tahap "diproses" belum keputusan, jadi dibiarkan kosong.
            'diverifikasi_pada' => in_array($status, ['disetujui', 'ditolak'], true) ? now() : null,
        ]);

        Notification::make()
            ->title("Pengajuan {$record->nomor}: " . strtolower($record->labelStatus()))
            ->color(['diproses' => 'info', 'disetujui' => 'success', 'ditolak' => 'danger'][$status])
            ->icon(['diproses' => Heroicon::OutlinedArrowPath, 'disetujui' => Heroicon::OutlinedCheckCircle, 'ditolak' => Heroicon::OutlinedXCircle][$status])
            ->send();
    }
}
