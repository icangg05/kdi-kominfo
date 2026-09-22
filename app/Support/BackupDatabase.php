<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Dump SQL (struktur + isi semua tabel) tanpa mysqldump, jadi image PHP tidak perlu klien MySQL. */
final class BackupDatabase
{
    private const BARIS_PER_INSERT = 500;

    /** @param  resource  $keluar */
    public static function tulis($keluar): void
    {
        $db = DB::connection();
        $mysql = $db->getDriverName() === 'mysql';
        $q = $db->getQueryGrammar();

        fwrite($keluar, "-- Backup {$db->getDatabaseName()}, " . WaktuWita::sekarang()->toDateTimeString() . " WITA\n");
        if ($mysql) {
            fwrite($keluar, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n");
        }

        foreach (self::tabel() as ['name' => $tabel]) {
            // SQLite hanya untuk test; indeksnya tidak ikut.
            $buat = $mysql
                ? $db->selectOne('SHOW CREATE TABLE ' . $q->wrapTable($tabel))->{'Create Table'}
                : $db->scalar('select sql from sqlite_master where name = ?', [$tabel]);

            fwrite($keluar, "\nDROP TABLE IF EXISTS {$q->wrapTable($tabel)};\n$buat;\n");

            // ponytail: cursor() MySQL tetap di-buffer PDO, jadi memori ≈ tabel terbesar;
            // pindah ke query unbuffered kalau tabel `visitor` sudah jutaan baris.
            foreach ($db->table($tabel)->cursor()->chunk(self::BARIS_PER_INSERT) as $kumpulan) {
                $kolom = implode(', ', array_map($q->wrap(...), array_keys((array) $kumpulan->first())));
                $nilai = $kumpulan->map(fn (object $baris) => '(' . implode(', ', array_map(
                    fn ($v) => $v === null ? 'NULL' : $db->getPdo()->quote((string) $v),
                    (array) $baris,
                )) . ')')->implode(",\n");

                fwrite($keluar, "INSERT INTO {$q->wrapTable($tabel)} ($kolom) VALUES\n$nilai;\n");
            }
        }

        if ($mysql) {
            fwrite($keluar, "\nSET FOREIGN_KEY_CHECKS=1;\n");
        }
    }

    /** Data + indeks semua tabel dalam byte, dari statistik InnoDB (bisa tertinggal sampai 24 jam). */
    public static function ukuran(): int
    {
        return (int) array_sum(array_column(self::tabel(), 'size'));
    }

    /** Tanpa skema, MySQL mengembalikan tabel dari semua database yang bisa diakses user. */
    private static function tabel(): array
    {
        return Schema::getTables(Schema::getCurrentSchemaListing());
    }
}
