<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDinas;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
  public function index()
  {
    $data  = ProfilDinas::where('jenis', 'pengaturan')->firstOrFail();
    $telp  = $data->konten[0]['value'];
    $email = $data->konten[1]['value'];
    $fb    = $data->konten[2]['value'];
    $ig    = $data->konten[3]['value'];
    $tt    = $data->konten[4]['value'];
    $yt    = $data->konten[5]['value'];

    return view('admin.form-pengaturan', compact(
      'telp',
      'email',
      'fb',
      'ig',
      'tt',
      'yt',
    ));
  }


  public function update(Request $request)
  {
    $request->validate([
      'telp'  => 'required',
      'email' => 'required|email',
      'fb'    => 'nullable|url',
      'ig'    => 'nullable|url',
      'tt'    => 'nullable|url',
      'yt'    => 'nullable|url',
    ]);

    $data   = ProfilDinas::where('jenis', 'pengaturan')->firstOrFail();
    $konten = $data->konten;

    // Loop dan update value dari setiap item berdasarkan id
    foreach ($konten as &$item) {
      if (isset($item['id']) && $request->has($item['id'])) {
        $item['value'] = $request->input($item['id']);
      }
    }

    // Simpan kembali ke database
    $data->konten = json_encode($konten);
    $data->save();

    return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
  }
}
