<?php

namespace App\Providers;

use App\Support\NamaBerkas;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Table;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    Carbon::setLocale(config('app.locale'));

    Table::configureUsing(fn (Table $table) => $table->defaultPaginationPageOption(25));

    // Nama file pendek (NamaBerkas), menggantikan ULID 26 karakter bawaan FileUpload dan
    // hash 40 karakter bawaan lampiran RichEditor. Ekstensinya tetap mengikuti bawaan
    // masing-masing: FileUpload dari nama asli, lampiran editor ditebak dari MIME.
    FileUpload::configureUsing(fn (FileUpload $upload) => $upload->getUploadedFileNameForStorageUsing(
      fn (TemporaryUploadedFile $file) => NamaBerkas::acak($file->getClientOriginalExtension()),
    ));
    RichEditor::configureUsing(fn (RichEditor $editor) => $editor->saveUploadedFileAttachmentUsing(
      fn (RichEditor $component, TemporaryUploadedFile $file) => $file->storeAs(
        $component->getFileAttachmentsDirectory(),
        NamaBerkas::acak($file->guessExtension()),
        $component->getFileAttachmentsDiskName(),
      ),
    ));

    // Bawaan Livewire menolak upload sementara di atas 12 MB dan membatalkan
    // upload yang lebih lama dari 5 menit — terlalu ketat untuk dokumen 100 MB.
    // Temp upload wajib di disk privat. Endpoint upload Livewire bisa dipanggil tamu
    // (halaman login pun komponen Filament) dan menyimpan file dengan ekstensi dari klien,
    // jadi di disk public file .html/.php kiriman siapa pun langsung bisa dibuka via /storage.
    // RichEditor mengirim perubahan per path JSON; teks di butir daftar sudah 11 segmen
    // (data.konten.content.N.content.N.content.N.content.N.text), tiap daftar bersarang +4,
    // tautan/tabel menambah lagi. Bawaan 10 membuat simpan gagal, 30 cukup untuk daftar 4 tingkat.
    config([
      'livewire.temporary_file_upload.disk' => 'local',
      'livewire.temporary_file_upload.rules' => ['required', 'file', 'max:' . config('app.upload.file_maks_kb')],
      'livewire.temporary_file_upload.max_upload_time' => 30,
      'livewire.payload.max_nesting_depth' => 30,
    ]);

    // Sapu sisa upload PHP tiap ada aktivitas Livewire/Filament (buka form,
    // klik, upload). Digantung di RequestHandled, bukan langsung di boot(),
    // karena di bawah Octane boot() cuma jalan sekali per worker — request()
    // di sini tidak mewakili request mana pun.
    // ponytail: nebeng request, bukan cron — kita tidak punya akses ke server.
    // Pindahkan ke Schedule kalau nanti cron di server sudah benar-benar jalan.
    Event::listen(function (RequestHandled $event) {
      if ($event->request->is('livewire/*')) {
        self::sweepStaleUploadTemps(sys_get_temp_dir());
      }
    });
  }

  /**
   * Hapus sisa upload PHP yang nyangkut (mis. /tmp/phpA7f3K2).
   *
   * PHP menaruh tiap file yang diupload di upload_tmp_dir (default /tmp) dan
   * baru menghapusnya kalau request selesai normal. Upload yang putus di
   * tengah jalan — timeout, koneksi drop, user menekan "coba lagi" — tidak
   * pernah dibersihkan siapa pun, menumpuk sampai /tmp penuh dan semua upload
   * berikutnya ikut gagal.
   *
   * Berjalan sebagai user PHP sendiri, jadi hanya menyentuh file miliknya.
   * Kalau php-fpm/FrankenPHP memakai PrivateTmp, sys_get_temp_dir() sudah
   * menunjuk ke /tmp privat itu — justru satu-satunya cara mudah
   * membersihkannya.
   *
   * @param  int  $minAge  Umur minimum (detik) sebelum file dianggap sampah.
   *                       Default 1 jam supaya tidak membunuh upload yang
   *                       masih berjalan di request lain.
   * @return int  Jumlah file yang terhapus.
   */
  public static function sweepStaleUploadTemps(string $dir, int $minAge = 3600): int
  {
    $batas = time() - $minAge;
    $terhapus = 0;

    // Pola 'php' + 6 karakter acak, persis format tempnam() milik PHP, supaya
    // tidak menyenggol php-fpm.sock atau file lain yang kebetulan berawalan php.
    foreach (glob(rtrim($dir, '/') . '/php??????') ?: [] as $sisa) {
      $diubah = @filemtime($sisa);

      // filemtime() bisa false kalau request lain sudah menghapusnya duluan.
      if ($diubah !== false && $diubah < $batas && is_file($sisa) && @unlink($sisa)) {
        $terhapus++;
      }
    }

    return $terhapus;
  }
}
