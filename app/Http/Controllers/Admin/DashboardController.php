<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    $totalPegawai = Pegawai::count();
    $totalBerita  = Berita::count();
    $totalDokumen = Dokumen::count();
    $totalGaleri  = Galeri::count();

    return view('admin.dashboard', compact(
      'totalPegawai',
      'totalBerita',
      'totalDokumen',
      'totalGaleri',
    ));
  }
}
