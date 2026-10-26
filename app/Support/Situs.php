<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

/** Pembantu tampilan situs publik: alamat berkas, format tanggal/angka, dan origin publik. */
final class Situs
{
  /** Sampul pengganti untuk data tanpa gambar, juga dipakai bila gambarnya gagal dimuat. */
  public const GAMBAR_DEFAULT = '/img/gambar-default.webp';

  /** Isi atribut onerror pada <img>: ganti ke sampul bawaan, sekali saja supaya tidak berulang. */
  public const CADANGAN_GAMBAR = "this.onerror=null;this.src='/img/gambar-default.webp'";

  /**
   * Path relatif (/storage/...), bukan URL absolut, supaya tetap jalan dari domain mana pun
   * situs disajikan. Null bila berkasnya tidak ada di disk, supaya situs memakai gambar default.
   */
  public static function url(?string $path): ?string
  {
    $path = ltrim((string) $path, '/');

    return $path !== '' && Storage::disk('public')->exists($path) ? "/storage/{$path}" : null;
  }

  /** Mis. 26 Oktober 2026; kosong bila tanggal tidak ada. */
  public static function tanggal(CarbonInterface|string|null $tanggal): string
  {
    return $tanggal ? Carbon::parse($tanggal)->locale('id')->translatedFormat('j F Y') : '';
  }

  /** Pemisah ribuan Indonesia: 12.345. */
  public static function angka(int|float|string|null $n): string
  {
    return Number::format((int) $n, locale: 'id');
  }

  /** Ukuran berkas yang mudah dibaca, mis. 1,4 MB. */
  public static function ukuran(int $byte): string
  {
    $satuan = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $nilai = $byte;

    while ($nilai >= 1024 && $i < count($satuan) - 1) {
      $nilai /= 1024;
      $i++;
    }

    return Number::format($nilai, maxPrecision: $i ? 1 : 0, locale: 'id') . ' ' . $satuan[$i];
  }

  /** Nomor telepon untuk tautan tel:, hanya angka dan tanda plus. */
  public static function tel(string $telp): string
  {
    return 'tel:' . preg_replace('/[^+\d]/', '', $telp);
  }

  /**
   * Origin publik untuk URL absolut (canonical, og:image, sitemap). Di balik proxy, request bisa
   * masih http, jadi APP_URL diutamakan.
   */
  public static function asal(): string
  {
    return rtrim((string) config('app.url'), '/');
  }
}
