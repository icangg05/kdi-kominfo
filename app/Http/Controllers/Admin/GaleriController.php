<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
  public function index()
  {
    $data = Galeri::orderBy('id', 'desc')->get();
    return view('admin.form-galeri', compact(
      'data'
    ));
  }


  public function store(Request $request)
  {
    $request->validate([
      'judul'   => 'required',
      'tanggal' => 'required|date',
      'gambar'  => 'required|image|max:2048',
    ]);

    $path          = $request->file('gambar')->store('galeri', 'public');
    $data          = new Galeri();
    $data->judul   = $request->judul;
    $data->tanggal = $request->tanggal;
    $data->gambar  = $path;
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'judul'   => 'required',
      'tanggal' => 'required|date',
      'gambar'  => 'image|max:2048',
    ]);

    $data = Galeri::findOrFail($id);

    if ($request->file('gambar')) {
      Storage::disk('public')->delete($data->gambar);
      $path = $request->file('gambar')->store('galeri', 'public');
    }

    $data->judul   = $request->judul;
    $data->tanggal = $request->tanggal;
    $data->gambar  = $path ?? $data->gambar;
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function destroy($id)
  {
    $data = Galeri::findOrFail($id);
    $data->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus.');
  }
}
