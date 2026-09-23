<?php

namespace Tests\Feature;

use App\Filament\Resources\Beritas\Pages\ListBeritas;
use App\Filament\Resources\Dokumens\Pages\ListDokumens;
use App\Filament\Resources\Galeris\Pages\ListGaleris;
use App\Filament\Resources\Pegawais\Pages\ListPegawais;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TabelAdminTest extends TestCase
{
  use RefreshDatabase;

  public static function halaman(): array
  {
    return [
      'berita' => [ListBeritas::class],
      'galeri' => [ListGaleris::class],
      'video' => [ListVideos::class],
      'dokumen' => [ListDokumens::class],
      'pegawai' => [ListPegawais::class],
    ];
  }

  #[\PHPUnit\Framework\Attributes\DataProvider('halaman')]
  public function test_tabel_punya_kolom_nomor_urut(string $halaman): void
  {
    $this->seed();
    $this->actingAs(User::first());

    Livewire::test($halaman)->assertTableColumnExists('no');
  }

  public function test_tabel_berita_tidak_menampilkan_sampul(): void
  {
    $this->seed();
    $this->actingAs(User::first());

    Livewire::test(ListBeritas::class)->assertTableColumnDoesNotExist('thumbnail');
  }

  public function test_tabel_pegawai_urut_dari_data_terbaru(): void
  {
    $this->seed();
    $this->actingAs(User::first());
    $jabatan = Jabatan::create(['nama' => 'Staf']);
    $lama = Pegawai::create(['nama' => 'Andi', 'jabatan_id' => $jabatan->id, 'created_at' => now()->addDay()]);
    $baru = Pegawai::create(['nama' => 'Zaki', 'jabatan_id' => $jabatan->id, 'created_at' => now()->addDays(2)]);

    Livewire::test(ListPegawais::class)->assertCanSeeTableRecords([$baru, $lama], inOrder: true);
  }
}
