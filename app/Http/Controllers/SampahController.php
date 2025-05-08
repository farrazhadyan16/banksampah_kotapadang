<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sampah;

class SampahController extends Controller
{
    public function index()
    {
        $listsampah = Sampah::all();
        return view('sampah.index', compact('listsampah'));
    }
//     public function edit($id)
// {
//     $sampah = Sampah::findOrFail($id);
//     return view('admin.edit', compact('sampah'));
// }

public function update(Request $request, $id)
{
    $request->validate([
        'jenis_sampah' => 'required|string',
        'harga_satuan' => 'required|numeric|min:0',
        'jumlah' => 'required|numeric|min:0',
    ]);

    $sampah = Sampah::findOrFail($id);
    $sampah->update($request->only('jenis_sampah', 'harga_satuan', 'jumlah'));

    return redirect()->back()->with('success', 'Data sampah berhasil diperbarui!');
}
// SampahController.php
public function destroy($id)
{
    $sampah = Sampah::findOrFail($id);
    $sampah->delete();
    return redirect()->route('sampah.index')->with('success', 'Data berhasil dihapus.');
}

}