<?php

namespace App\Http\Controllers;

use App\Models\ProfilPimpinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPimpinanController extends Controller
{
  public function index()
  {
    $data = ProfilPimpinan::firstOrFail();

    return view('admin.form-profil-pimpinan', compact('data'));
  }

  public function save(Request $request)
  {
    $request->validate([
      'nama'          => 'required',
      'awal_periode'  => 'required',
      'akhir_periode' => 'required',
      'konten'        => 'required|string',
      'foto-kadis-1'  => 'image|max:2048',
      'foto-kadis-2'  => 'image|max:2048',
      'foto-kadis-3'  => 'image|max:2048',
    ]);

    $data = ProfilPimpinan::firstOrFail();
    $foto = $data->foto ?? [];

    // cek jika ada input file yang dikirim
    foreach ($foto as $i => &$item) {
      $inputName = "foto-kadis-" . $item['id'];
      if ($request->hasFile($inputName)) {
        // Hapus file lama
        if (Storage::disk('public')->exists($item['value'])) {
          Storage::disk('public')->delete($item['value']);
        }

        // Simpan file baru
        $path = $request->file($inputName)->store('foto-kadis', 'public');
        $item['value'] = $path;
      }
    }

    $data->update([
      'nama'          => $request->nama,
      'awal_periode'  => $request->awal_periode,
      'akhir_periode' => $request->akhir_periode,
      'konten'        => $request->konten,
      'foto'          => json_encode($foto),
    ]);

    return back()->with('success', 'Data berhasil disimpan.');
  }


  // public function delete(Request $request, $jenis)
  // {
  //   if (!array_key_exists($jenis, $this->viewMap)) {
  //     abort(404);
  //   }

  //   $data   = ProfilDinas::where('jenis', $jenis)->firstOrFail();
  //   $konten = is_array($data->konten) ? $data->konten : json_decode($data->konten, true);

  //   // Khusus jenis foto, hapus juga file gambarnya
  //   if ($jenis === 'foto-diskominfo') {
  //     foreach ($konten as $item) {
  //       if ($item['id'] == $request->dataId) {
  //         Storage::disk('public')->delete($item['value']);
  //         break;
  //       }
  //     }
  //   }

  //   // Hapus item dari array berdasarkan id
  //   $konten = array_values(array_filter($konten, function ($item) use ($request) {
  //     return $item['id'] != $request->dataId;
  //   }));

  //   $data->update(['konten' => json_encode($konten)]);

  //   return back()->with('success', 'Data berhasil dihapus.');
  // }
}
