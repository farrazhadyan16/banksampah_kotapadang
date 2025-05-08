<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showNasabah()
    {
        $listnasabah = User::where('role', 'nasabah')->get();
        return view('admin.user_nasabah', compact('listnasabah'));
    }
    public function showAdmin()
    {
        $listadmin = User::where('role', 'admin')->get();
        return view('admin.user_admin', compact('listadmin'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email', // otomatis validasi mengandung '@'
            'no_hp' => 'required|numeric|digits_between:10,15',
            'alamat' => 'required'
        ], [
            'email.email' => 'Format email tidak valid.',
            'no_hp.digits_between' => 'No HP Minimal 10-15 Angka.',
        ]);
    
        $user = User::findOrFail($id);
    
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);
    
        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }
        public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data nasabah berhasil dihapus!');
    }


}