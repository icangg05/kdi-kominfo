<?php

namespace App\Support;

use Illuminate\Support\Str;

/** Nama acak pendek untuk semua berkas yang disimpan aplikasi (upload form, lampiran editor, sampul berita). */
final class NamaBerkas
{
    public static function acak(?string $ekstensi): string
    {
        return Str::random(10) . ($ekstensi ? ".{$ekstensi}" : '');
    }
}
