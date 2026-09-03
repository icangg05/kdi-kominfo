<?php

require __DIR__ . '/../vendor/autoload.php';

/*
 * Compose menyuntikkan DB_* sebagai variabel lingkungan asli, yang di CLI mendarat
 * di $_SERVER. Laravel membaca $_SERVER lebih dulu daripada $_ENV, jadi
 * <env force="true"> di phpunit.xml kalah dan pengujian menghantam basis data
 * pengembangan — RefreshDatabase sempat menghapusnya dua kali. Dipaksa di sini.
 */
foreach (['DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => ':memory:'] as $kunci => $nilai) {
  $_SERVER[$kunci] = $_ENV[$kunci] = $nilai;
  putenv("$kunci=$nilai");
}
