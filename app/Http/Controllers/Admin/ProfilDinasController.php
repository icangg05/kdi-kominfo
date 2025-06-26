<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDinas;
use Illuminate\Http\Request;

class ProfilDinasController extends Controller
{
  protected $viewMap = [
    'sejarah' => 'admin.form-sejarah',
    'visi'    => 'admin.form-visi',
    'misi'    => 'admin.form-misi',
  ];

  public function index($jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) abort(404);

    $data = ProfilDinas::where('jenis', $jenis)->first();

    return view($this->viewMap[$jenis], compact('data'));
  }

  public function save(Request $request, $jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) abort(404);

    $request->validate([
      'konten' => 'required|string',
    ]);

    $data   = ProfilDinas::where('jenis', $jenis)->first();
    $konten = $data->konten ?? [];

    if ($jenis === 'misi') {
      if ($request->filled('misiId')) {
        // Update item berdasarkan ID
        foreach ($konten as &$item) {
          if ($item['id'] == $request->misiId) {
            $item['misi'] = ucfirst($request->konten);
            break;
          }
        }
      } else {
        // Tambah baru
        $konten[] = [
          'id' => end($konten) ?end($konten)['id'] + 1 : 1,
          'misi' => ucfirst($request->konten),
        ];
        $konten = json_encode($konten);
      }
    } else {
      $konten = $request->konten;
    }

    $data->update(['konten' => $konten]);

    return back()->with('success', 'Data berhasil disimpan.');
  }


  public function delete(Request $request, $jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) abort(404);

    $data   = ProfilDinas::where('jenis', $jenis)->firstOrFail();
    $konten = $data->konten ?? [];

    // Pastikan ada misiId
    if ($request->filled('misiId')) {
      // Filter array, hapus item yang cocok dengan misiId
      $konten = array_filter($konten, function ($item) use ($request) {
        return $item['id'] != $request->misiId;
      });

      // Simpan ke database
      $data->update(['konten' => json_encode($konten)]);

      return back()->with('success', 'Data berhasil dihapus.');
    }
  }
}
