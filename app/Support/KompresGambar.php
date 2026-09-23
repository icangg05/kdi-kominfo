<?php

namespace App\Support;

use Filament\Forms\Components\BaseFileUpload;
use GdImage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/** Kompres gambar upload ke WebP; pengaturannya di config('app.upload.kompres_gambar'). */
final class KompresGambar
{
    private const MIME = ['image/jpeg', 'image/png', 'image/webp'];

    /** Pengganti penyimpanan bawaan Filament: ->saveUploadedFileUsing(KompresGambar::simpan(...)). */
    public static function simpan(BaseFileUpload $component, TemporaryUploadedFile $file): ?string
    {
        $atur = config('app.upload.kompres_gambar');

        if (! $atur['aktif'] || ! in_array($file->getMimeType(), self::MIME, true)) {
            return $component->saveUploadedFile($file);
        }

        $asli = $file->get();
        $webp = self::keWebp($asli, $atur['lebar_maks'], $atur['kualitas']);

        // Gambar yang sudah hemat bisa malah membengkak setelah di-encode ulang.
        if ($webp === null || strlen($webp) >= strlen($asli)) {
            return $component->saveUploadedFile($file);
        }

        $path = trim($component->getDirectory() . '/' . NamaBerkas::acak('webp'), '/');
        $component->getDisk()->put($path, $webp, $component->getVisibility());

        return $path;
    }

    /** Null kalau GD tidak bisa membaca datanya (rusak, GIF animasi, dsb.). */
    public static function keWebp(string $data, int $lebarMaks, int $kualitas): ?string
    {
        $info = getimagesizefromstring($data);
        $gambar = $info === false ? false : @imagecreatefromstring($data);

        if ($gambar === false) {
            return null;
        }

        imagepalettetotruecolor($gambar);

        if ($info[2] === IMAGETYPE_JPEG) {
            $gambar = self::luruskan($gambar, $data);
        }

        if (imagesx($gambar) > $lebarMaks) {
            $gambar = imagescale($gambar, $lebarMaks);
        }

        imagesavealpha($gambar, true);
        ob_start();
        imagewebp($gambar, null, $kualitas);

        return ob_get_clean();
    }

    // Foto ponsel disimpan miring dengan tag EXIF Orientation. WebP tidak membawa
    // EXIF itu, jadi pikselnya harus diputar dulu atau hasilnya tampil miring.
    private static function luruskan(GdImage $gambar, string $data): GdImage
    {
        $aliran = fopen('php://memory', 'r+');
        fwrite($aliran, $data);
        rewind($aliran);
        $exif = @exif_read_data($aliran) ?: [];
        fclose($aliran);

        // ponytail: orientasi cermin (2, 4, 5, 7) diabaikan, hampir tidak pernah keluar dari kamera.
        return match ($exif['Orientation'] ?? 1) {
            3 => imagerotate($gambar, 180, 0),
            6 => imagerotate($gambar, -90, 0),
            8 => imagerotate($gambar, 90, 0),
            default => $gambar,
        };
    }
}
