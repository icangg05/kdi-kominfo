<?php

namespace App\Support;

use App\Models\PengajuanLayanan;
use App\Models\ProfilDinas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Notifikasi ke chat, grup, atau channel Telegram lewat Bot API. Sakelar, token bot, dan ID chat diatur admin di
 * Pengaturan Situs (baris `pengaturan` di profil_dinas). Token disimpan terenkripsi dengan APP_KEY supaya tidak
 * terbaca dari backup database.
 */
final class Telegram
{
  /** Bot API biasanya membalas dalam hitungan detik; lebih dari ini dianggap gagal. */
  private const BATAS_DETIK = 10;

  /** Galat Bot API yang paling sering ditemui operator, diterjemahkan beserta jalan keluarnya. */
  private const GALAT = [
    'Unauthorized' => 'Token bot tidak dikenali Telegram. Salin ulang token dari @BotFather.',
    'chat not found' => 'Chat tidak ditemukan. Periksa ID chat dan pastikan bot sudah ditambahkan ke channel atau grup tersebut.',
    'not enough rights' => 'Bot belum boleh mengirim pesan. Jadikan bot admin channel dengan izin mengirim pesan.',
    'need administrator rights' => 'Bot belum boleh mengirim pesan. Jadikan bot admin channel dengan izin mengirim pesan.',
    'bot was kicked' => 'Bot sudah dikeluarkan dari channel atau grup. Tambahkan kembali bot tersebut.',
    'bot is not a member' => 'Bot belum menjadi anggota channel atau grup. Tambahkan bot lebih dulu.',
  ];

  /** Ikon jenis layanan di judul pesan pengajuan; kuncinya sama dengan PengajuanLayanan::JENIS. */
  private const IKON_LAYANAN = [
    'subdomain-hosting' => '🌐',
    'email-dinas' => '📧',
    'pengajuan-tte' => '✍️',
  ];

  /**
   * Kirim pesan HTML, dengan tombol tautan (label => URL) di bawahnya bila ada. Null bila terkirim; selain itu
   * penjelasan galat yang bisa ditampilkan ke admin.
   */
  public static function kirim(string $token, string $chat, string $html, array $tombol = []): ?string
  {
    $data = [
      'chat_id' => $chat,
      'text' => $html,
      'parse_mode' => 'HTML',
      'link_preview_options' => ['is_disabled' => true],
    ];

    if ($tombol && collect($tombol)->every(fn (string $url) => self::alamatPublik($url))) {
      $data['reply_markup'] = ['inline_keyboard' => [
        collect($tombol)->map(fn (string $url, string $label) => ['text' => $label, 'url' => $url])->values()->all(),
      ]];
    } elseif ($tombol) {
      // Telegram menolak seluruh pesan bila URL tombol bukan alamat publik, mis. APP_URL http://localhost:8000
      // di komputer pengembang. Di sana tautannya ditulis sebagai teks supaya pesan tetap sampai.
      $data['text'] .= "\n\n" . collect($tombol)->map(fn (string $url, string $label) => '<b>' . e($label) . ': ' . e($url) . '</b>')->implode("\n");
    }

    try {
      $balasan = Http::timeout(self::BATAS_DETIK)->post("https://api.telegram.org/bot{$token}/sendMessage", $data);
    } catch (Throwable $e) {
      // Pesan galat koneksi memuat URL lengkap, termasuk token bot.
      return 'Tidak dapat terhubung ke Telegram: ' . str_replace($token, '***', $e->getMessage());
    }

    if ($balasan->json('ok')) {
      return null;
    }

    $keterangan = (string) ($balasan->json('description') ?? "HTTP {$balasan->status()}");

    foreach (self::GALAT as $cari => $terjemahan) {
      if (Str::contains($keterangan, $cari, ignoreCase: true)) {
        return "{$terjemahan} ({$keterangan})";
      }
    }

    return $keterangan;
  }

  /** Pemberitahuan pengajuan baru ke chat yang diatur admin; galatnya hanya dicatat, pemohon tidak boleh ikut gagal. */
  public static function pengajuanBaru(PengajuanLayanan $pengajuan): void
  {
    $atur = ProfilDinas::pengaturan();
    $token = self::buka($atur['telegram_token'] ?? null);
    $chat = trim((string) ($atur['telegram_chat_id'] ?? ''));

    if (! ($atur['telegram_aktif'] ?? false) || ! $token || $chat === '') {
      return;
    }

    if ($galat = self::kirim($token, $chat, self::pesanPengajuan($pengajuan), self::tombolPengajuan($pengajuan))) {
      Log::warning("Notifikasi Telegram untuk pengajuan {$pengajuan->nomor} gagal: {$galat}");
    }
  }

  /**
   * Ringkasan pengajuan untuk petugas. NIK, NIP, tanggal lahir, dan nomor HP tidak ikut: pesan Telegram bisa
   * diteruskan ke luar, sedangkan data itu cukup dibuka di panel.
   */
  public static function pesanPengajuan(PengajuanLayanan $pengajuan): string
  {
    $rincian = $pengajuan->rincian ?? [];
    $jenisLayanan = $rincian['jenis_layanan'] ?? null;
    // Sama dengan tab "Perlu ditindak" di daftar pengajuan admin, termasuk pengajuan ini.
    $antrean = PengajuanLayanan::whereIn('status', ['menunggu', 'diproses'])->count();

    return collect([
      '📥 <b>PENGAJUAN LAYANAN BARU</b>',
      '',
      (self::IKON_LAYANAN[$pengajuan->layanan] ?? '🗂️') . ' <b>' . e($pengajuan->labelLayanan()) . '</b>',
      '🔖 Nomor: <b>' . e($pengajuan->nomor) . '</b>',
      '🏢 Instansi: <b>' . e($pengajuan->instansi) . '</b>',
      '👤 Pemohon: <b>' . e($pengajuan->nama_pemohon) . '</b>',
      isset($rincian['jabatan']) ? '💼 Jabatan: <b>' . e($rincian['jabatan']) . '</b>' : null,
      isset($rincian['subdomain']) ? '🔗 Subdomain: <b>' . e($rincian['subdomain'] . '.' . PengajuanLayanan::DOMAIN) . '</b>' : null,
      $jenisLayanan ? '🏷️ Jenis layanan: <b>' . e(PengajuanLayanan::JENIS_LAYANAN_DOMAIN[$jenisLayanan] ?? $jenisLayanan) . '</b>' : null,
      '📎 Berkas: <b>' . ($pengajuan->ktp ? 'surat permohonan dan KTP' : 'surat permohonan') . '</b>',
      '🕒 Masuk: <b>' . Situs::tanggalJam($pengajuan->created_at) . '</b>',
      '',
      "⏳ Perlu ditindak saat ini: <b>{$antrean} pengajuan</b>",
    ])->reject(fn (?string $baris) => $baris === null)->implode("\n");
  }

  /**
   * Tombol ke rincian pengajuan ini di panel. Petugas yang belum login diarahkan Filament ke halaman masuk, lalu
   * kembali ke tujuan. Alamat memakai APP_URL: di balik proxy, request bisa masih http atau berhost internal.
   *
   * @return array<string, string>
   */
  public static function tombolPengajuan(PengajuanLayanan $pengajuan): array
  {
    return [
      '🔍 Periksa pengajuan' => Situs::asal() . route('filament.admin.resources.pengajuan-layanan.view', $pengajuan, absolute: false),
    ];
  }

  public static function pesanUji(): string
  {
    return "✅ <b>Uji notifikasi berhasil</b>\n\nBot situs Diskominfo Kota Kendari sudah terhubung. Pengajuan layanan baru akan diberitahukan ke chat ini.";
  }

  /** Alamat yang diterima Telegram sebagai tombol: bernama domain, bukan localhost atau alamat IP. */
  private static function alamatPublik(string $url): bool
  {
    $host = (string) parse_url($url, PHP_URL_HOST);

    return str_contains($host, '.') && filter_var($host, FILTER_VALIDATE_IP) === false;
  }

  /** Token untuk disimpan di basis data. */
  public static function kunci(?string $token): ?string
  {
    $token = trim((string) $token);

    return $token === '' ? null : Crypt::encryptString($token);
  }

  /** Token dari basis data; null bila kosong atau tak bisa dibuka (mis. APP_KEY sudah diganti). */
  public static function buka(?string $tersimpan): ?string
  {
    if (blank($tersimpan)) {
      return null;
    }

    try {
      return Crypt::decryptString($tersimpan);
    } catch (DecryptException) {
      return null;
    }
  }
}
