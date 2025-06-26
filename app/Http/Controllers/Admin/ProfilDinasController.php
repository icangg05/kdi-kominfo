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
    'tugas'   => 'admin.form-tugas',
    'fungsi'  => 'admin.form-fungsi',
  ];

  public function index($jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) {
      abort(404);
    }
    $data = ProfilDinas::where('jenis', $jenis)->firstOrFail();

    return view($this->viewMap[$jenis], compact('data'));
  }

  public function save(Request $request, $jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) {
      abort(404);
    }

    $data = ProfilDinas::where('jenis', $jenis)->firstOrFail();

    if (in_array($jenis, ['misi', 'fungsi'])) {
      $request->validate(['konten' => 'required|string']);

      $konten = $data->konten ?? [];

      if ($request->filled('misiId')) {
        foreach ($konten as &$item) {
          if ($item['id'] == $request->misiId) {
            $item['value'] = ucfirst($request->konten);
            if ($jenis === 'fungsi') {
              $item['icon'] = $request->icon;
            }
            break;
          }
        }
      } else {
        $nextId = count($konten) > 0 ? max(array_column($konten, 'id')) + 1 : 1;
        $newItem = [
          'id' => $nextId,
          'value' => ucfirst($request->konten),
        ];
        if ($jenis === 'fungsi') {
          $newItem['icon'] = $request->icon;
        }
        $konten[] = $newItem;
      }

      $data->update(['konten' => json_encode($konten)]);
    } else {
      // Sejarah & Visi & Tugas
      $request->validate(['konten' => 'required|string']);
      $data->update(['konten' => $request->konten]);
    }

    return back()->with('success', 'Data berhasil disimpan.');
  }

  public function delete(Request $request, $jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) {
      abort(404);
    }

    $data = ProfilDinas::where('jenis', $jenis)->firstOrFail();
    $konten = is_array($data->konten) ? $data->konten : json_decode($data->konten, true);

    if ($request->filled('misiId')) {
      $konten = array_values(array_filter($konten, function ($item) use ($request) {
        return $item['id'] != $request->misiId;
      }));

      $data->update(['konten' => json_encode($konten)]);
      return back()->with('success', 'Data berhasil dihapus.');
    }

    return back()->with('error', 'Data tidak ditemukan.');
  }
}
