<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriDokumen;
use Illuminate\Http\Request;

class KategoriDokumenController extends Controller
{
  public function index()
  {
    $data = KategoriDokumen::all();
    return view('admin.form-kategori-dokumen', compact('data'));
  }


  public function store(Request $request)
  {
    $request->validate(['nama' => 'required|unique:kategori_berita']);

    $data       = new KategoriDokumen();
    $data->nama = ucfirst($request->nama);
    $data->slug = str()->slug($request->nama);
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function update(Request $request, $id)
  {
    $request->validate(['nama' => 'required|unique:kategori_berita,nama,' . $id]);

    $data       = KategoriDokumen::findOrFail($id);
    $data->nama = ucfirst($request->nama);
    $data->slug = str()->slug($request->nama);
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function destroy($id)
  {
    $data = KategoriDokumen::findOrFail($id);
    $data->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus.');
  }
}
