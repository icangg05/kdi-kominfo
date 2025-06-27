<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBerita;
use Illuminate\Http\Request;

class KategoriBeritaController extends Controller
{
  public function index()
  {
    $data = KategoriBerita::all();
    return view('admin.form-kategori-berita', compact('data'));
  }


  public function store(Request $request)
  {
    $request->validate(['nama' => 'required|unique:kategori_berita']);

    $data       = new KategoriBerita();
    $data->nama = ucfirst($request->nama);
    $data->slug = str()->slug($request->nama);
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function update(Request $request, $id)
  {
    $request->validate(['nama' => 'required|unique:kategori_berita,nama,' . $id]);

    $data       = KategoriBerita::findOrFail($id);
    $data->nama = ucfirst($request->nama);
    $data->slug = str()->slug($request->nama);
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function destroy($id)
  {
    $data = KategoriBerita::findOrFail($id);
    $data->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus.');
  }
}
