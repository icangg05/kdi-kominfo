<?php

namespace App\Support;

use Carbon\CarbonInterface;

/** Jam dan sapaan menurut WITA, sama dengan jam di resources/js/situs.js. */
final class WaktuWita
{
    public static function sekarang(): CarbonInterface
    {
        return now('Asia/Makassar');
    }

    public static function sapaan(?CarbonInterface $waktu = null): string
    {
        $jam = ($waktu ?? self::sekarang())->hour;

        return match (true) {
            $jam < 4 => 'Selamat malam',
            $jam < 11 => 'Selamat pagi',
            $jam < 15 => 'Selamat siang',
            $jam < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };
    }
}
