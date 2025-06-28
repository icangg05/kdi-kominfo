<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
  public function index()
  {
    $data = Dokumen::latest()->get();
    return view('admin.form-dokumen', compact(
      'data'
    ));
  }


  public function store(Request $request)
  {
    $request->validate([
      'judul'               => 'required',
      'kategori_dokumen_id' => 'required',
      'deskripsi'           => 'required',
      'file'                => 'required|mimes:pdf,docx|max:51200',
    ]);

    $path = $request->file('file')->store('dokumen', 'public');

    $data                      = new Dokumen();
    $data->judul               = ucfirst($request->judul);
    $data->deskripsi           = ucfirst($request->deskripsi);
    $data->kategori_dokumen_id = $request->kategori_dokumen_id;
    $data->file                = $path;
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'judul'               => 'required',
      'kategori_dokumen_id' => 'required',
      'deskripsi'           => 'required',
      'file'                => 'mimes:pdf,docx|max:51200',
    ]);

    if ($request->file('file'))
      $path = $request->file('file')->store('dokumen', 'public');

    $data                      = Dokumen::findOrFail($id);
    $data->judul               = ucfirst($request->judul);
    $data->deskripsi           = ucfirst($request->deskripsi);
    $data->kategori_dokumen_id = $request->kategori_dokumen_id;
    $data->file           = $path ?? $data->file;
    $data->save();

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
  }


  public function destroy($id)
  {
    $data = Dokumen::findOrFail($id);
    $data->delete();
    return redirect()->back()->with('success', 'Data berhasil dihapus.');
  }
}
