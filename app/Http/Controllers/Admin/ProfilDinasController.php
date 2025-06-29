<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilDinasController extends Controller
{
  protected $viewMap = [
    'sejarah'             => 'admin.form-sejarah',
    'visi'                => 'admin.form-visi',
    'misi'                => 'admin.form-misi',
    'tugas'               => 'admin.form-tugas',
    'fungsi'              => 'admin.form-fungsi',
    'foto-diskominfo'     => 'admin.form-foto-diskominfo',
    'struktur-organisasi' => 'admin.form-struktur-organisasi',

    // Sambutan & tagline
    'sambutan-kadis'   => ' admin.form-sambutan-kadis',
    'tagline-sambutan' => ' admin.form-tagline',
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

    if (in_array($jenis, ['misi', 'tagline-sambutan', 'fungsi'])) {
      $request->validate(['konten' => 'required|string']);

      $konten = $data->konten ?? [];

      if ($request->filled('dataId')) {
        foreach ($konten as &$item) {
          if ($item['id'] == $request->dataId) {
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
    } elseif ($jenis == 'foto-diskominfo') {
      // Foto diskominfo
      $request->validate(['konten' => 'required|image|max:2048']);
      $path = $request->file('konten')->store('foto-diskominfo', 'public');

      $konten = $data->konten ?? [];
      $nextId = count($konten) > 0 ? max(array_column($konten, 'id')) + 1 : 1;

      $konten[] = [
        'id'    => $nextId,
        'value' => $path,
      ];

      $data->update(['konten' => json_encode($konten)]);
    } else {
      if ($jenis == 'struktur-organisasi') {
        $request->validate(['konten' => 'required|image|max:2048']);
        $path = $request->file('konten')->store('struktur-organisasi', 'public');

        Storage::disk('public')->delete($data->konten);

        $data->update(['konten' => $path]);
      } else {
        // Sejarah & Visi & Tugas
        $request->validate(['konten' => 'required|string']);
        $data->update(['konten' => $request->konten]);
      }
    }

    return back()->with('success', 'Data berhasil disimpan.');
  }


  public function delete(Request $request, $jenis)
  {
    if (!array_key_exists($jenis, $this->viewMap)) {
      abort(404);
    }

    $data   = ProfilDinas::where('jenis', $jenis)->firstOrFail();
    $konten = is_array($data->konten) ? $data->konten : json_decode($data->konten, true);

    // Khusus jenis foto, hapus juga file gambarnya
    if ($jenis == 'foto-diskominfo') {
      foreach ($konten as $item) {
        if ($item['id'] == $request->dataId) {
          Storage::disk('public')->delete($item['value']);
          break;
        }
      }
    }

    // Hapus item dari array berdasarkan id
    $konten = array_values(array_filter($konten, function ($item) use ($request) {
      return $item['id'] != $request->dataId;
    }));

    $data->update(['konten' => json_encode($konten)]);

    return back()->with('success', 'Data berhasil dihapus.');
  }
}
