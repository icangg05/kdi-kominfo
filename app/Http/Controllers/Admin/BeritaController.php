<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
  public function index()
  {
    $data = Berita::latest()->get();
    return view('admin.form-berita', compact(
      'data'
    ));
  }


  public function store(Request $request)
  {
    $request->validate([
      'judul'              => 'required',
      'kategori_berita_id' => 'required',
      'tanggal'            => 'required',
      'konten'             => 'required',
      'thumbnail'          => 'image|max:2048',
    ]);

    if ($request->file('thumbnail'))
      $path = $request->file('thumbnail')->store('gambar', 'public');

    $data                     = new Berita();
    $data->judul              = ucfirst($request->judul);
    $data->slug               = str()->slug($request->judul);
    $data->tanggal            = $request->tanggal;
    $data->konten             = $request->konten;
    $data->kategori_berita_id = $request->kategori_berita_id;
    $data->thumbnail          = $path ?? null;
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'judul'              => 'required',
      'kategori_berita_id' => 'required',
      'tanggal'            => 'required',
      'konten'             => 'required',
      'thumbnail'          => 'image|max:2048',
    ]);

    if ($request->file('thumbnail'))
      $path = $request->file('thumbnail')->store('gambar', 'public');

    $data                     = Berita::findOrFail($id);
    $data->judul              = ucfirst($request->judul);
    $data->slug               = str()->slug($request->judul);
    $data->tanggal            = $request->tanggal;
    $data->konten             = $request->konten;
    $data->kategori_berita_id = $request->kategori_berita_id;
    $data->thumbnail          = $path ?? $data->thumbnail;
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function destroy($id)
  {
    $data = Berita::findOrFail($id);
    $data->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus.');
  }
}
