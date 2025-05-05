<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sampah;

class SampahController extends Controller
{
    public function index()
    {
        $sampahs = Sampah::all();
        return view('sampah.index', compact('sampahs'));
    }
    public function edit($id)
{
    $sampah = Sampah::findOrFail($id);
    return view('admin.sampah.edit', compact('sampah'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'jenis_sampah' => 'required|string|max:255',
        'harga_satuan' => 'required|numeric',
    ]);

    $sampah = Sampah::findOrFail($id);
    $data = $request->except(['_token', '_method']);
    $sampah->update($data);

    return redirect()->route('sampah.index')->with('success', 'Data berhasil diperbarui!');
}
// SampahController.php
public function destroy($id)
{
    $sampah = Sampah::findOrFail($id);
    $sampah->delete();
    return redirect()->route('sampah.index')->with('success', 'Data berhasil dihapus.');
}

}