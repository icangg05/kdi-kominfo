<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
  public function index()
  {
    $data = Pegawai::with('jabatan')->latest()->get();

    return view('admin.form-pegawai', compact('data'));
  }

  public function refreshTable()
  {
    $data = Pegawai::with('jabatan')->latest()->get();

    return view('components.partials-admin.pegawai-table', compact('data'));
  }


  protected function create($request)
  {
    if ($request->hasFile('foto'))
      $path = $request->file('foto')->store('foto-pegawai', 'public');

    Pegawai::create([
      'nama'          => $request->nama,
      'jabatan_id'    => $request->jabatan_id,
      'tanggal_lahir' => $request->tanggal_lahir,
      'nip'           => $request->nip,
      'alamat'        => $request->alamat,
      'foto'          => $path ?? null,
    ]);
  }

  protected function update($request)
  {
    $data = Pegawai::findOrFail($request->dataId);

    if ($request->hasFile('foto')) {
      Storage::disk('public')->delete($data->foto);
      $path = $request->file('foto')->store('foto-pegawai', 'public');
    }

    $data->update([
      'nama'          => $request->nama,
      'jabatan_id'    => $request->jabatan_id,
      'tanggal_lahir' => $request->tanggal_lahir,
      'nip'           => $request->nip,
      'alamat'        => $request->alamat,
      'foto'          => $path ?? $data->foto,
    ]);
  }

  public function save(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'nama'       => 'required',
      'jabatan_id' => 'required',
      'foto'       => 'image|max:2048',
    ]);

    if ($validator->fails())
      return response()->json(['errors' => $validator->errors()], 422);

    if ($request->dataId)
      $this->update($request);
    else
      $this->create($request);

    return response()->json(['message' => 'Data berhasil disimpan.']);
  }


  public function delete(Request $request)
  {
    $pegawai = Pegawai::findOrFail($request->dataId);
    if ($pegawai->foto)
      Storage::disk('public')->delete($pegawai->foto);
    $pegawai->delete();

    return response()->json(['message' => 'Data berhasil dihapus.']);
  }
}
