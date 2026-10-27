<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Merapikan teks isian formulir publik sebelum disimpan. Hanya huruf awal yang dinaikkan; sisanya dibiarkan
 * seperti diketik, jadi singkatan (OPD, UPTD, BPKAD) dan gelar tidak ikut rusak.
 */
final class Teks
{
    /** Kata tugas tetap huruf kecil di tengah nama, mis. "Dinas Pemberdayaan Perempuan dan Perlindungan Anak". */
    private const KATA_TUGAS = ['dan', 'atau', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'dengan', 'bagi', 'serta', 'oleh', 'dalam', 'tentang', 'terhadap'];

    /** Nama orang, tempat, instansi, jabatan: "dinas kesehatan  kota kendari" jadi "Dinas Kesehatan Kota Kendari". */
    public static function kapitalTiapKata(string $teks): string
    {
        return collect(explode(' ', Str::squish($teks)))
            ->map(fn (string $kata, int $i) => $i > 0 && in_array($kata, self::KATA_TUGAS, true) ? $kata : Str::ucfirst($kata))
            ->implode(' ');
    }

    /** Kalimat seperti deskripsi: cukup huruf pertamanya, baris baru dibiarkan. */
    public static function kapitalAwal(string $teks): string
    {
        return Str::ucfirst(trim($teks));
    }
}
